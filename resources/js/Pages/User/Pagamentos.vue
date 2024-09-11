<template>
    <app-layout>
        <div class="dashboard">
            <v-card elevation="0" class="my-5">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">Pagamentos ({{ facturas.length }})</h3>
                    </v-col>

                      <v-col class="text-right">
                    <v-btn color="#00987b" dark class="mb-2 mx-4 font-weight-bold teal white--text" rounded
                                outlined>
                                <inertia-link class="text-white" href="/financas/factura">
                                    Gerar Factura
                                </inertia-link>
                            </v-btn>
                    </v-col> 
                </v-row>
            </v-card>
            <v-container>
                <v-card class="elevation-0">
                    <template>
                        <v-data-table :headers="headers" :items="facturas" :search="search">
                            <template v-slot:item.estadoPagamento="{ item }">
                                <v-chip :color="getColor(item.estado_factura_id)" dark>
                                    {{ item.estado_Pagamento.designacao }}
                                </v-chip>
                            </template>
                            <template v-slot:item.numero="{ index }">
                                <tr>
                                    <td>{{ index + 1 }}</td>
                                </tr>
                            </template>
                            <template slot="no-data">
                                sem nenhum dado
                            </template>
                            <template slot="no-results">
                                não foi encontrado nehum dado na pesquisa
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <button text @click="editItem(item)">
                                    <v-icon color="blue" icon large class="mr-2" title="Editar" :disabled="item.estado_factura_id == 6 ||
                                        item.estado_factura_id == 7 ||
                                        item.estado_factura_id == 4
                                        ">
                                        mdi-pencil
                                    </v-icon>
                                </button>
                                <button text @click="deleteItem(item)">
                                    <v-icon color="red" large icon title="Apagar factura" :disabled="!user.can['Eliminar tarefas'] ||
                                        item.estado_factura_id == 4
                                        ">
                                        mdi mdi-trash-can-outline
                                    </v-icon>
                                </button>
                                <button text @click="LiquidarPagamento(item)">
                                    <v-icon v-if="item.estado_factura_id == 5" color="black" large icon title="Pagar"
                                        :disabled="!user.can['Eliminar tarefas'] ||
                                            item.estado_factura_id == 3
                                            ">
                                        mdi mdi-cash-clock
                                    </v-icon>
                                    <v-icon color="green" large icon title="Validar pagamento"
                                        v-else-if="item.estado_factura_id == 1">
                                        mdi mdi-cash
                                    </v-icon>
                                    <v-icon color="green" large icon title="Visualizar pagamento"
                                        v-else>
                                        visibility
                                    </v-icon>

                                </button>
                     <!--            <v-icon color="green" icon @click="verDetalhe(item)" title="Visualizar detalhes">
                                    visibility
                                </v-icon> -->
                            </template>
                        </v-data-table>
                        <v-card-text> </v-card-text>
                    </template>
                </v-card>
            </v-container>
            <v-dialog v-if="dialogPagamento" v-model="dialogPagamento" persistent width="700px">
                <v-card>
                    <v-toolbar class="text-uppercase font-weight-bold">
                        <v-toolbar-title>
                            {{
                                editedIndex > -1
                                    ? "Editar Pagamento"
                                    : "Realizar Pagamento"
                            }}</v-toolbar-title>
                    </v-toolbar>
                    <v-form ref="formPagamentos" lazy-validation>
                        <v-container>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="6" sm="6" xs="12">
                                        <v-textarea dense label="Descriçao da factura" v-model="pagamento.descricao"
                                            readonly prepend-icon="description" :rules="nomePagamentoRules"
                                            :error-messages="erros.nome_Pagamento" required rows="3"></v-textarea>
                                    </v-col>
                                    <v-col cols="6" sm="6" xs="12">
                                        <v-col cols="12" >
                                            <template v-if="pagamento.estado_factura_id==5">
                                                <v-file-input label="Foto do comprovativoo" dense show-size counter
                                                    multiple accept="application/image/jpeg,image/png" v-model="adicionar_pagamento.imagem_pagamento
                                                        "></v-file-input>
                                            </template>
                                            <v-row v-else>
                                                <v-col v-for="foto in imagens " :key="foto.id"  :md="imagens.length> 1? 1:6 ">
                                                    <v-btn icon text color="#00897B" @click="VisualizarFotos(foto)">
                                                <v-icon :title="foto.designacao">
                                                    mdi mdi-image
                                                </v-icon>
                                            </v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col cols="12" md="12">
                                            <v-autocomplete v-model="adicionar_pagamento.forma_pagamento_id"
                                                placeholder="Tipo de pagamento" :items="forma_pagamentos"
                                                item-text="designacao" item-value="id" prepend-icon="description">

                                            </v-autocomplete>
                                        </v-col>
                                      <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field prepend-icon="description"
                                                v-model="adicionar_pagamento.nome_banco" 
                                                placeholder="Nome do banco">

                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field prepend-icon="description"
                                                v-model="adicionar_pagamento.valor_depositado" type="number" nim="1"
                                                placeholder="total geral">

                                            </v-text-field>
                                        </v-col>
                                      </v-row>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn outlined rounded dark @click="cancelarDialog()" color="red">Voltar</v-btn>
                            <v-btn :disabled="pagamento.estado_factura_id == 2 || pagamento.estado_factura_id == 3 ||pagamento.estado_factura_id == 4" outlined rounded @click="salvarPagamento()" color="#00897B">{{
                                    pagamento.estado_factura_id == 1 ? "Validar Pagamento" : " Salvar" }}</v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDelete" v-model="dialogDelete" max-width="500px">
                <v-card>
                    <v-card-title class="text-h6">Tens a certeza que pretendes eliminar a
                        Pagamento?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined color="red" dark @click="closeDelete()">não</v-btn>
                        <v-btn rounded outlined color="#00897B" dark @click="deleteItemConfirm()">sim</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDetalheFactura" v-model="dialogDetalheFactura" fullscreen hide-overlay
                transition="dialog-bottom-transition">
                <v-card class="grey lighten-5">
                    <v-toolbar class="text-h6 text-dark dark">
                        <v-spacer></v-spacer>
                        Detalhes da Factura
                        <v-btn icon dark @click="dialogDetalheFactura = false">
                            <v-icon color="black">mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-spacer></v-spacer>
                    <v-card-actions>
                        <v-btn color="black" text :href="'/Pagamentos/arquivo-pdf-Pagamento/' + pagamento.id" target="__blank"
                            class="remover-link">
                            Exportar Dados:
                            <v-icon title="Exportar em pdf" color="black">
                                mdi-file-export</v-icon>
                        </v-btn>
                    </v-card-actions>
                    <v-container>
                        <v-card>
                            <v-list three-line subheader>
                                <v-row justify="space-around">
                                    <v-col cols="12" sm="7" class="ma-3 pa-0">
                                        <v-sheet class="ma-0 pa-0">
                                            <v-subheader class="font-weight-bold ml-0 mb-2 teal--text text-uppercase">
                                                <span style="font-weight: bolder">
                                                    DADOS DA Pagamento
                                                </span>
                                            </v-subheader>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Nome</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        pagamento.nome_Pagamento
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold">Percentagem</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        pagamento.percentagem
                                                        }}%</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Dias de Execução
                                                    </v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        pagamento.tempo_execucao
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold">Estado</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        pagamento
                                                            .estado_Pagamento
                                                            .designacao
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>

                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Data de
                                                        Inicio</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        {{
                                                            pagamento.data_inicio_real
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Data
                                                        Final</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        {{
                                                            pagamento.data_fim_real
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-sheet>
                                    </v-col>
                                    <v-divider class="ma-0 pa-0" vertical></v-divider>
                                    <v-col cols="12" sm="4" class="ma-3 pa-0">
                                        <v-sheet class="ma-0 pa-0">
                                            <v-subheader
                                                class="font-weight-bold ml-0 mb-2 teal--text text-uppercase"><span
                                                    style="font-weight: bolder">DADOS DO PROJECTO
                                                </span></v-subheader>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Nome</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        pagamento.projecto
                                                            .nome_proj
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">
                                                        Prioridade</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        {{
                                                            pagamento.projecto
                                                                .prioridade_proj
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">
                                                        Estado</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        {{
                                                            pagamento.projecto
                                                                .estado_projeto
                                                                .designacao
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-sheet>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-card elevation="0">
                                    <v-card-actions class="justify-center">
                                        <v-text class="text-h6 teal--text" @click="show = !show">
                                            Clique para ver a descrição da
                                            Pagamento
                                        </v-text>

                                        <v-btn icon @click="show = !show">
                                            <v-icon>{{
                                                show
                                                    ? "mdi-chevron-up"
                                                    : "mdi-chevron-down"
                                            }}</v-icon>
                                        </v-btn>
                                    </v-card-actions>

                                    <v-expand-transition v-model="panel_motivo">
                                        <div v-show="show">
                                            <v-divider></v-divider>

                                            <v-card-text class="text-justify white text--corprincipal"
                                                v-html="pagamento.descricao">
                                            </v-card-text>
                                        </div>
                                    </v-expand-transition>
                                </v-card>
                            </v-list>
                        </v-card>

                        <v-card v-if="pagamento.control_Pagamentos" class="">
                            <v-card-actions class="pa-0">
                                <v-subheader class="font-weight-bold ml-0 mb-2 teal--text text-uppercase"><span
                                        style="font-weight: bolder">LISTA DE RESPONSAVEIS
                                    </span></v-subheader>

                                <v-spacer></v-spacer>

                                <v-btn icon @click="show2 = !show2">
                                    <v-icon>{{
                                        show2
                                            ? "mdi-chevron-up"
                                            : "mdi-chevron-down"
                                    }}</v-icon>
                                </v-btn>
                            </v-card-actions>

                        </v-card>
                      
                    </v-container>
                    <div style="flex: 1 1 auto"></div>
                </v-card>
            </v-dialog>
            <v-dialog v-if="dialogRejeitarPagamento" v-model="dialogRejeitarPagamento" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5">Informe o motivo da rejeição</v-card-title>
                    <v-form ref="formRejeitarPagamento" lazy-validation>
                        <v-card-text>
                            <v-text-field label="Motivo da Rejeição da Pagamento" :rules="motivoRegeicaoRules"
                                :aria-errormessage="erros.regeicao" v-model="pagamento.motivo_rejeicao"
                                prepend-icon="description">
                            </v-text-field>
                        </v-card-text>
                    </v-form>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color=" red " text @click="cancelarDialogRejeitarPagamento()">Cancel</v-btn>
                        <v-btn color="#00897B" text @click="saveRejeitarPagamento()">Adicionar</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogCancelaPagamento" v-model="dialogCancelaPagamento" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5">Informe o motivo do cancelamento</v-card-title>
                    <v-form ref="formCancelaPagamento" lazy-validation>
                        <v-card-text>
                            <v-text-field label="Motivo do Cancelamento da Pagamento" :rules="motivocancelamentoRules"
                                :aria-errormessage="erros.regeicao" v-model="pagamento.motivo_cancelamento"
                                prepend-icon="description">
                            </v-text-field>
                        </v-card-text>
                    </v-form>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color=" red " text @click="cancelarDialogCancelaPagamento()">Cancel</v-btn>
                        <v-btn color="#00897B" dark rounded outlined @click="saveCancelaPagamento()">Adicionar</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-row justify="center">
                <v-dialog v-if="dialogDetalheFactura" v-model="dialogDetalheFactura" fullscreen hide-overlay
                    transition="dialog-bottom-transition">
                    <v-card class="green lighten-5">
                        <v-toolbar class="text-h5 text-white corprincipal">
                            <v-spacer></v-spacer>
                            Detalhes da Pagamento
                            <v-btn icon dark @click="dialogDetalheFactura = false">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container>
                            <v-card-text>
                                <v-card class="ma-0">
                                    <v-subheader class="headertop font-weight-regular">
                                        <span style="font-weight: bolder">
                                            DADOS DA Pagamento
                                        </span>
                                        <v-spacer></v-spacer>
                                        <span>
                                            <a :href="'/Pagamentos/arquivo-pdf-Pagamento/' +
                                                pagamento.id
                                                " target="__blank" class="remover-link">
                                                <v-icon title="Exportar em pdf" color="#fff">
                                                    mdi-file-export</v-icon>
                                                         </a> </span>
                                    </v-subheader>
                                    <v-row class="mx-auto text-left">
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Data de Inicio da
                                                    Pagamento</strong><br />
                                                {{ pagamento.data_inicio_real }}
                                                <br /><br />
                                                <strong>Percentagem da
                                                    Pagamento</strong><br />
                                                {{ pagamento.percentagem }}%
                                            </div>
                                        </v-col>
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Dias de Execução
                                                    Pagamento</strong><br />
                                                {{ pagamento.tempo_execucao }}
                                                <br /><br />
                                                <strong>Estado da Pagamento</strong><br />
                                                {{
                                                    pagamento.estado_Pagamento
                                                        .designacao
                                                }}
                                            </div>
                                        </v-col>
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Data Final da
                                                    Pagamento</strong><br />
                                                {{ pagamento.data_fim_real }}
                                                <br /><br />
                                                <!-- <strong>Data de validade</strong><br />
                      {{

                      }} -->
                                            </div>
                                        </v-col>
                                    </v-row>

                                    <v-row class="mx-auto text-left">
                                        <v-col cols="4"> </v-col>
                                    </v-row>

                                    <v-expansion-panels v-model="panel_motivo">
                                        <v-expansion-panel>
                                            <v-expansion-panel-header>
                                                <header class="font-weight-bold ml-0 mb-2 blue--text">
                                                    <v-icon title="Descrição do Comunicado">dehaze</v-icon>
                                                    Clique para ver a dercição
                                                    da Pagamento
                                                </header>
                                            </v-expansion-panel-header>

                                            <v-expansion-panel-content>
                                                <v-row class="mx-auto">
                                                    <v-col cols="12" md="6">
                                                    </v-col>
                                                </v-row>

                                                <v-card-text class="text-justify white text--corprincipal"
                                                    v-html="pagamento.nome_Pagamento">
                                                </v-card-text>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </v-card>
                                <v-card class="my-5 elevation-5">
                                    <v-subheader class="headertop font-weight-regular"><span
                                            style="font-weight: bolder">DADOS DO
                                            PROJECTO
                                        </span></v-subheader>
                                    <v-row class="mx-auto text-left">
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Nome do projecto</strong><br /><br />
                                                dash{{
                                                    pagamento.projecto.nome_proj
                                                }}
                                            </div>
                                        </v-col>

                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>
                                                    Prioridade do
                                                    Projecto:</strong><br /><br />
                                                {{
                                                    pagamento.projecto
                                                        .prioridade_proj
                                                }}
                                            </div>
                                        </v-col>

                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Estado do Projecto</strong><br /><br />
                                                {{
                                                    pagamento.projecto
                                                        .estado_projeto
                                                        .designacao
                                                }}
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card>
                            
                            </v-card-text>
                        </v-container>
                        <div style="flex: 1 1 auto"></div>
                    </v-card>
                </v-dialog>
            </v-row>

        </div>
    </app-layout>
</template>

<script>
import { isExists } from "date-fns/esm";
import AppLayout from "../../Shared/AppLayout";
import TabApartamento from "../../components/TabApartamento";
import Factura from "./Factura.vue";
import axios from "axios";

const gradients = [
    ["#222"],
    ["#42b3f4"],
    ["red", "orange", "yellow"],
    ["purple", "violet"],
    ["#00c6ff", "#F0F", "#FF0"],
    ["#f72047", "#ffd200", "#1feaea"],
];
export default {
    // props são variaveis que podem ser acessadas em qualquer parte do projeto e aqui estou a usalos para armazenas os dados vindo do banco de dado.
    props: [
        "msg",
        "facturas",
        "forma_pagamentos",
        "responsaveis",
        "estado_tarefas",
        "control_tarefas",
        "funcao",
        "index",
        "projecto_marcado",
    ],
    components: {
        AppLayout,
        TabApartamento,
    },

    data() {
        return {
                        imagens: [],
            dialogPagamento: false,
            dialogDelete: false,
            dialogDetalheFactura: false,
            panel_motivo: "",
            dialogRejeitarPagamento: false,
            dialogCancelaPagamento: false,
            adicionar_pagamento: {},
            idprojecto: "",
            pagamento: {
            },
            defaultpagamento: {
            },
            search: "",
            erros: [],
            nomePagamentoRules: [(v) => !!v || "Campo Obrigatório"],
            descricacaoPagamentoRules: [
                (v) =>
                    !!v ||
                    "A descrição da Pagamento tem de ser clara e sugestiva com no minimo de 20 caracter para consiguir se ter a ideia da Pagamento",
                (v) =>
                    (v && v.length > 0) ||
                    "A descrição da Pagamento é muito curta",
            ],
            referencaProjetoRules: [(v) => !!v || "Campo Obrigatório"],
            dataFinalFiltrolRules: {
                Valido(value, data) {
                    return (
                        value >= data ||
                        "A data final não pode ser inferior que a data inicial"
                    );
                },
            },
            responsavelRules: [(v) => !!v || "Capmo Obrigatorio"],

            headers: [
                {
                    text: "Nº da factura",
                    value: "faturaReference",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Descricao",
                    value: "descricao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Apartamneto",
                    value: "apartamento.designacao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Estado",
                    value: "estado_fatura.designacao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Total do preço",
                    value: "total_preco_factura",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "total_iva",
                    value: "total_iva",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "total a pagar",
                    value: "total_preco_factura",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
               
                {
                    text: "Emitida por",
                    value: "pessoa.nome_pessoa",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },



                {
                    text: "Opções",
                    value: "actions",
                    align: "left",
                    class: "font-weight-bold black--text subtitle-1 my-3",
                    sortable: false,
                },
            ],
        };
    },
    methods: {
        VisualizarFotos(item) {
            window.open('/storage/' +item.imagem_pagamento, '_blank')
        },
        PagamentoPendente(item) {
            window.open("certificado-entrada/" + btoa(btoa(btoa(item.id))));
        },
        validate() {
            this.$refs.form.validate();
        },
        getColor(estado_factura_id) {
            if (estado_factura_id == 1) {
                return "orange";
            } else if (estado_factura_id == 2) {
                return "blue";
            } else if (estado_factura_id == 3) {
                return "orange";
            } else if (estado_factura_id == 4) {
                return "green";
            } else if (estado_factura_id == 5) {
                return "red";
            } else if (estado_factura_id == 6) {
                return "red";
            } else if (estado_factura_id == 7) {
                return "red";
            } else return "green";
        },
        aceitarPagamento() {
            // this.editedIndex = this.Pagamentos.indexOf(item)
            // this.pagamento = Object.assign({}, item);
            this.$inertia.put(
                "/Pagamentos/aceitar-Pagamento/" + this.pagamento.id,
                this.pagamento,
                {
                    onFinish: () => {
                        if (this.$page.props.flash.success != null) {
                            Vue.toasted.global.defaultSuccess({
                                msg: "" + this.$page.props.flash.success,
                            });
                        }
                        if (this.$page.props.flash.error != null) {
                            Vue.toasted.global.defaultError({
                                msg: "" + this.$page.props.flash.error,
                            });
                        }
                    },
                }
            );
        },

        rejeitarPagamento(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogRejeitarPagamento = true;
        },
        cancelarDialogRejeitarPagamento() {
            this.dialogRejeitarPagamento = false;
            this.$nextTick(() => {
                this.pagamento = Object.assign({}, this.defaultpagamento);
                this.editedIndex = -1;
            });
        },

        saveRejeitarPagamento() {
            if (this.$refs["formRejeitarPagamento"].validate()) {
                this.$inertia.put(
                    "/Pagamentos/rejeitar-Pagamento/" + this.pagamento.id,
                    this.pagamento,
                    {
                        onFinish: () => {
                            if (this.$page.props.flash.success != null) {
                                Vue.toasted.global.defaultSuccess({
                                    msg: "" + this.$page.props.flash.success,
                                });
                            }
                            if (this.$page.props.flash.error != null) {
                                Vue.toasted.global.defaultError({
                                    msg: "" + this.$page.props.flash.error,
                                });
                            }
                            this.cancelardialogRejeitarPagamento();
                        },
                    }
                );
            }
        },
        cancelaPagamento(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogCancelaPagamento = true;
        },
        cancelarDialogCancelaPagamento() {
            this.dialogCancelaPagamento = false;
            this.$nextTick(() => {
                this.pagamento = Object.assign({}, this.defaultpagamento);
            });
        },

        saveCancelaPagamento() {
            if (this.$refs["formCancelaPagamento"].validate()) {
                this.$inertia.put(
                    "/Pagamentos/cancelamento-Pagamento/" + this.pagamento.id,
                    this.pagamento,
                    {
                        onFinish: () => {
                            if (this.$page.props.flash.success != null) {
                                Vue.toasted.global.defaultSuccess({
                                    msg: "" + this.$page.props.flash.success,
                                });
                            }
                            if (this.$page.props.flash.error != null) {
                                Vue.toasted.global.defaultError({
                                    msg: "" + this.$page.props.flash.error,
                                });
                            }
                            this.cancelarDialogCancelaPagamento();
                        },
                    }
                );
            }
        },

        LiquidarPagamento(item) {

            this.pagamento = Object.assign({}, item);
            this.editedIndex = -1;
            
            if(this.pagamento.estado_factura_id!=5){
                axios.post('/financas/imagens-pagamentos',{
                    factura_id:this.pagamento.id
                })
                .then((response)=>{
                    this.imagens=response.data.imagens
                   this.adicionar_pagamento.forma_pagamento_id=response.data.pagamento_feito.forma_pagamento_id
                   this.adicionar_pagamento.nome_banco=response.data.pagamento_feito.nome_banco
                   this.adicionar_pagamento.valor_depositado=response.data.pagamento_feito.valor_depositado
                })

            }
            
            this.dialogPagamento = true;
        },

        editItem(item) {
            this.filtrarProjectoResponsavel(item.projecto_id);
            this.editedIndex = this.Pagamentos.indexOf(item);
            this.pagamento = Object.assign({}, item);
            this.dialog = true;
        },
        verDetalhe(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogDetalheFactura = true;
        },
        deleteItem(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.pagamento = Object.assign({}, this.defaultpagamento);
                this.editedIndex = -1;
            });
        },

        deleteItemConfirm() {
            this.$inertia.delete("/financas/eliminar-factura/" + this.pagamento.id, {
                onFinish: () => {
                    if (this.$page.props.flash.success != null) {
                        Vue.toasted.global.defaultSuccess({
                            msg: "" + this.$page.props.flash.success,
                        });
                    }
                    if (this.$page.props.flash.error != null) {
                        Vue.toasted.global.defaultError({
                            msg: "" + this.$page.props.flash.error,
                        });
                    }
                },
            }),
                this.closeDelete();
        },
        cancelarDialog() {
            this.pagamento = Object.assign({}, this.defaultpagamento);
            this.editedIndex = -1;
            this.dialogPagamento = false;
        },
        salvarPagamento() {
            if (this.$refs["formPagamentos"].validate()) {
                if (this.pagamento.estado_factura_id==1) {
                    this.$inertia.put(
                        `/financas/validar-pagamento/${this.pagamento.id}`,
                        this.pagamento,
                        {
                            onFinish: () => {
                                if (this.$page.props.flash.success != null) {
                                    Vue.toasted.global.defaultSuccess({
                                        msg:
                                            "" + this.$page.props.flash.success,
                                    });
                                }
                                if (this.$page.props.flash.error != null) {
                                    Vue.toasted.global.defaultError({
                                        msg: "" + this.$page.props.flash.error,
                                    });
                                }
                            },
                        }
                    );
                } else {
                    if (this.projecto_marcado) {
                        this.pagamento.projecto_id = this.projecto_marcado;
                    }
                    this.adicionar_pagamento.pagamento_faturas = this.pagamento
                    this.$inertia.post("/financas/adicionar-pagamento", this.adicionar_pagamento, {
                        onFinish: () => {
                            if (this.$page.props.flash.success != null) {
                                Vue.toasted.global.defaultSuccess({
                                    msg: "" + this.$page.props.flash.success,
                                });
                            }
                            if (this.$page.props.flash.error != null) {
                                Vue.toasted.global.defaultError({
                                    msg: "" + this.$page.props.flash.error,
                                });
                            }
                            this.cancelarDialog();
                        },
                    });
                }
            }
        },
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    mounted() {
    },

    created() {
    },

    progress() {
        this.dialog_info = false;
        this.dialog = true;
        setTimeout(() => {
            this.dialog = false;
        }, 7000);
    },
};
</script>

<style>
@import "vuetify/dist/vuetify.min.css";

.corprincipal {
    background-color: #0e85a3 !important;
}

.cortab {
    background-color: #05343f !important;
}

.headertop {
    color: #ffffff !important;
    background-color: #00897b !important;
    font-size: 1.1rem !important;
    font-weight: bold;

    /* background-color: #08a757 !important; */
}

.v-data-table header {
    font-size: 1px;
}

.v-data-table th {
    font-size: 9px;
}

.v-data-table td {
    font-size: 14px !important;
    /*   border-bottom: 0px solid !important; */
}
</style>
