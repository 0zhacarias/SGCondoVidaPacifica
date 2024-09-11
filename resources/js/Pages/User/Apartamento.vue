<template>
    <app-layout>
        <div class="dashboard">
            <v-card elevation="0">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">Apartamentos ({{ this.apartamentos? apartamentos.length:todos_apartamentos.length }})</h3>
                    </v-col>

                    <v-col class="text-right">
                        <v-btn color="corprincipal" title="Adicionar aparrtamento" class="white--text font-weight-bold"
                            @click="carregarDialog()">Adicionar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>

            <v-card class="my-5 elevation-0">
                <div class="text-h5 mx-2 font-weight-bold" style="color: #0a6e84">
                    Filtros <span class="mdi mdi-filter-outline"></span>
                </div>
                <v-card-title>
                    <v-row class="mx-2 mt-5">
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Bloco</label>
                            <!-- <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Pesquisar projectos"
                dense

              >
              </v-text-field> -->
                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query" item-value="id"
                                item-text="nome_proj" type="text" outlined clearable dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Sindico</label>
                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query" item-value="id"
                                item-text="nome_responsavel" type="text" outlined clearable dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">N apartamento</label>
                            <v-autocomplete @change="filtrarEstado()" clearable v-model="query" item-text="designacao"
                                item-value="id" prepend-icon="" outlined dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Tipologia</label>
                            <v-autocomplete @change="filtrarEstado()" clearable v-model="query" item-text="designacao"
                                item-value="id" prepend-icon="" label="Tipologia" outlined dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for=""></label>
                            <v-text-field @input="filtrarData()" label="Data de Criação" v-model="query" type="date"
                                outlined dense>
                            </v-text-field>
                        </v-col>

                        <v-col class="text-right">
                            <v-btn outlined rounded title="Adicionar" class="font-weight-bold"
                                @click="carregarDialog()">Pesquisar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-title>
            </v-card>
            <tab-apartamento v-if="apartamentos":apartamentos="apartamentos" :sindicos="sindicos"> </tab-apartamento>
            <v-container v-if="todos_apartamentos" >
                <v-card class="elevation-0">
                    <template>
                        <v-data-table :headers="headers" :items="todos_apartamentos" :search="search">
                            <template v-slot:item.estadoapartamento="{ item }">
                                <v-chip :color="getColor(item.estado_apartamento_id)" dark>
                                    {{ item.estado_apartamento.designacao }}
                                </v-chip>
                            </template>
                            <template v-slot:item.percentagem="{ item }">
                                <span>{{ item.percentagem }}%</span>
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
                                    <v-icon color="blue" icon large class="mr-2" title="Editar" :disabled="item.estado_apartamento_id == 6 ||
                                        item.estado_apartamento_id == 7 ||
                                        item.estado_apartamento_id == 4
                                        ">
                                        mdi-pencil
                                    </v-icon>
                                    Editar
                                </button>
                                <button text @click="deleteItem(item)">
                                    <v-icon color="red" large icon title="Apagar" :disabled="!user.can['Eliminar apartamentos'] ||
                                        item.estado_apartamento_id == 4
                                        ">
                                        mdi mdi-trash-can-outline
                                    </v-icon>
                                    Remover
                                </button>
                                <!--      <v-icon
                                        color="green"
                                        icon
                                        small
                                        @click="verDetalhe(item)"
                                        title="Visualizar detalhes"
                                    >
                                        visibility
                                    </v-icon> -->
                                <!--   <v-menu
                                        transition="slide-x-transition"
                                        bottom
                                        right
                                        v-if="item.estado_apartamento_id != 4"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                color="black"
                                                dark
                                                icon
                                                v-bind="attrs"
                                                v-on="on"
                                                title="Mais opções"
                                                small
                                            >
                                                <v-icon
                                                    >mdi-dots-vertical</v-icon
                                                >
                                            </v-btn>
                                        </template>

<v-list>
    <v-list-item v-if="
                                                    item.estado_apartamento_id ==
                                                        1 ||
                                                    item.estado_apartamento_id == 2
                                                ">
        <v-list-item-title>
            <a icon color="orange" class="text-decoration-none" @click="
                                                            adicionarResponsavel(
                                                                item
                                                            )
                                                        " title="Adicionar Responsavel">
                Adicionar Responsavel
            </a>
        </v-list-item-title>
    </v-list-item>
    <v-list-item v-if="
                                                    item.estado_apartamento_id ==
                                                        1 ||
                                                    item.estado_apartamento_id ==
                                                        2 ||
                                                    (item.estado_apartamento_id ==
                                                        3 &&
                                                        user.can[
                                                            'apartamento cancelada'
                                                        ])
                                                ">
        <v-list-item-title>
            <a icon color="orange" class="text-decoration-none" @click="
                                                            cancelaapartamento(item)
                                                        " title="Cancelar à apartamento">
                Cancelar à apartamento
            </a>
        </v-list-item-title>
    </v-list-item>

    <v-list-item v-if="
                                                    (item.created_by ==
                                                        user.id &&
                                                        item.percentagem ==
                                                            100 &&
                                                        item.estado_apartamento_id ==
                                                            3) ||
                                                    item.estado_apartamento_id == 5
                                                ">
        <v-list-item-title>
            <a icon color="orange" class="text-decoration-none" @click="
                                                            apartamentoConcluido(
                                                                item
                                                            )
                                                        " title="apartamento concluido">
                Concluir apartamento
            </a>
        </v-list-item-title>
    </v-list-item>
</v-list>
</v-menu> -->
                                <span> </span>
                            </template>
                        </v-data-table>
                        <v-card-title>
                            <v-spacer class="" vertical></v-spacer>
                            <!--   <div class="">
                                <v-btn
                                    v-if="user.can['Historico de apartamentos']"
                                    color="red"
                                    :href="'/apartamentos/pdf-historico-apartamentos/'"
                                    target="__blank"
                                    class="remover-link white--text font-weight-bold"
                                >
                                    PDF Histórico
                                    <v-icon
                                        title="Exportar em pdf"
                                        color="white"
                                    >
                                        mdi-file-export</v-icon
                                    >
                                </v-btn>
                                <v-btn
                                    color="black"
                                    :href="
                                        this.projecto_marcado
                                            ? '/apartamentos/listar-pdf-apartamentos/' +
                                              this.projecto_marcado
                                            : '/apartamentos/listar-pdf-apartamentos/' +
                                              null
                                    "
                                    target="__blank"
                                    class="remover-link white--text font-weight-bold"
                                >
                                    Exportar
                                    <v-icon
                                        title="Exportar em pdf"
                                        color="white"
                                    >
                                        mdi-file-export</v-icon
                                    >
                                </v-btn>
                                <v-btn
                                    bottom
                                    color="corprincipal"
                                    dark
                                    @click="carregarDialog()"
                                >
                                    <v-icon title="Adicionar apartamento"
                                        >add
                                    </v-icon>
                                </v-btn>

                            </div> -->
                        </v-card-title>

                        <v-card-text> </v-card-text>
                    </template>
                </v-card>
            </v-container>
            <v-dialog v-if="dialog" v-model="dialog" persistent width="700px">
                <v-card>
                    <v-toolbar class="text-uppercase font-weight-bold" elevation="2">
                        <v-toolbar-title>
                            {{
                                editedIndex > -1
                                    ? "Actualizar apartamento"
                                    : "Adicionar apartamento"
                            }}</v-toolbar-title>
                    </v-toolbar>
                    <v-form ref="formapartamentos" lazy-validation>
                        <v-container>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12">
                                        <v-textarea outlined dense label="Nome  da apartamento"
                                            v-model="apartamento.designacao" prepend-icon="description"
                                            :rules="nomeapartamentoRules" :error-messages="erros.designacao" required
                                            rows="1"></v-textarea>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea outlined dense label="Descrição da apartamento"
                                            v-model="apartamento.descricao" prepend-icon="description"
                                            :rules="descricacaoapartamentoRules" :error-messages="erros.descricao"
                                            required rows="3"></v-textarea>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col sm="12" md="6" v-if="!projecto_marcado">
                                        <v-autocomplete outlined label="Tipo de apartamento"
                                            v-model="apartamento.tipo_apartamento_id" :items="tipos_apartamento"
                                            item-text="descricao" :rules="referencaApartamentoRules" :error-messages="erros.nome_apartamneto
                                                " item-value="id" prepend-icon="folder" dense />
                                    </v-col>

                                    <v-col cols="12" :md="projecto_marcado ? 12 : 6">
                                        <v-autocomplete outlined multiple v-model="apartamento.condomino_id"
                                            :items="condominos" chips dense small-chips item-text="nome_pessoa"
                                            item-value="id" prepend-icon="person" label="Dono do apartamento"
                                            :rules="referencaResponsavelRules" :error-messages="erros.condomino_id
                                                " no-data-text="sem dados">

                                            <!--  <template v-slot:item="data">
                                                {{ data.item.nome_responsavel }}
                                                {{
                                                    data.item
                                                        .sobre_nome_responsavel
                                                }}
                                                ({{
                                                    data.item.funcao.designacao
                                                }})
                                            </template> -->
                                        </v-autocomplete>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col sm="12" md="12" v-if="editedIndex > -1">
                                        <v-text-field outlined prepend-icon="date_range" label="Data de ingresso (*)"
                                            v-model="apartamento.data_ingresso" type="date" dense required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col sm="12" md="12" v-else>
                                        <v-text-field outlined prepend-icon="date_range" label="Data da Criação(*)"
                                            v-model="apartamento.data_ingresso" :rules="dataInicioRules"
                                            :error-messages="erros.data_ingresso
                                                " type="date" dense required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn rounded outlined dark @click="cancelarDialog()" color="red">Cancelar</v-btn>
                                <v-btn rounded outlined dark @click="save()" color="#00897B">{{
                                    editedIndex > -1
                                        ? "Actualizar"
                                        : "adicionar"
                                }}</v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDelete" v-model="dialogDelete" max-width="500px">
                <v-card>
                    <v-card-title class="text-h6">Tens a certeza que pretendes eliminar a
                        apartamento?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined color="red" dark @click="closeDelete">não</v-btn>
                        <v-btn rounded outlined color="#00897B" dark @click="deleteItemConfirm">sim</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDetalheapartamento" v-model="dialogDetalheapartamento" fullscreen hide-overlay
                transition="dialog-bottom-transition">
                <v-card class="grey lighten-5">
                    <v-toolbar class="text-h6 text-dark dark">
                        <v-spacer></v-spacer>
                        Detalhes da apartamento
                        <v-btn icon dark @click="dialogDetalheapartamento = false">
                            <v-icon color="black">mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-spacer></v-spacer>
                    <v-card-actions>
                        <v-btn color="black" text :href="'/apartamentos/arquivo-pdf-apartamento/' + apartamento.id"
                            target="__blank" class="remover-link">
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
                                                    DADOS DA apartamento
                                                </span>
                                            </v-subheader>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Nome</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        apartamento.nome_apartamento
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold">Percentagem</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        apartamento.percentagem
                                                    }}%</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Dias de Execução
                                                    </v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        apartamento.tempo_execucao
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold">Estado</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">{{
                                                        apartamento
                                                            .estado_apartamento
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
                                                            apartamento.data_inicio_real
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title class="font-weight-bold">Data
                                                        Final</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        {{
                                                            apartamento.data_fim_real
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
                                                        apartamento.projecto
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
                                                            apartamento.projecto
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
                                                            apartamento.projecto
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
                                            apartamento
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
                                                v-html="apartamento.descricao">
                                            </v-card-text>
                                        </div>
                                    </v-expand-transition>
                                </v-card>
                            </v-list>
                        </v-card>

                        <v-card v-if="apartamento.control_apartamentos" class="">
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

                            <v-expand-transition>
                                <div v-show="show2">
                                    <v-data-table :headers="headersResponsavelDetalhes"
                                        :items="apartamento.control_apartamentos" hide-default-footer>
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon small @click="deleteResponsavel(item)">
                                                mdi-delete
                                            </v-icon>
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-expand-transition>
                        </v-card>
                        <v-card v-if="apartamento.percentagem_apartamento" class="">
                            <v-card-actions class="pa-0">
                                <v-subheader class="font-weight-bold ml-0 mb-2 teal--text text-uppercase"><span
                                        style="font-weight: bolder">HISTÓRICO DE PERCENGAGEM
                                    </span></v-subheader>
                                <v-spacer></v-spacer>

                                <v-btn icon @click="show3 = !show3">
                                    <v-icon>{{
                                        show3
                                            ? "mdi-chevron-up"
                                            : "mdi-chevron-down"
                                    }}</v-icon>
                                </v-btn>
                            </v-card-actions>

                            <v-expand-transition>
                                <div v-show="show3">
                                    <v-data-table :headers="headersPercentagemlDetalhes"
                                        :items="apartamento.percentagem_apartamento" hide-default-footer>
                                        <template slot="no-data">
                                            sem nenhum dado
                                        </template>
                                        <template slot="no-results">
                                            não foi encontrado nehum dado na
                                            pesquisa
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-expand-transition>
                        </v-card>
                    </v-container>
                    <div style="flex: 1 1 auto"></div>
                </v-card>
            </v-dialog>
            <v-dialog v-if="dialogResponsavel" v-model="dialogResponsavel" max-width="500px">
                <v-card>
                    <v-toolbar class="text-uppercase font-weight-bold" elevation="2">
                        <v-toolbar-title>
                            Adicionar responsaveis a apartamento
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="formResponsavel" lazy-validation>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-autocomplete multiple v-model="apartamento_responsavel.responsavel_id
                                            " chips small-chips :items="responsaveisapartamentos" item-text="name"
                                            item-value="id" prepend-icon="person" :menu-props="{
                                                bottom: true,
                                                offsetY: true,
                                            }">
                                        </v-autocomplete>

                                        <v-col cols="12"> </v-col>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="cancelarDialogResponsavel()">Cancelar</v-btn>
                        <v-btn v-if="apartamento_responsavel.responsavel_id.length > 0" dark color="#00897B"
                            @click="saveResponsavel()">
                            Adicionar</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-if="dialogDeleteResponsavel" v-model="dialogDeleteResponsavel" max-width="500px">
                <v-card>
                    <v-card-title class="text-h6">Tens a certeza que pretendes eliminar?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="closeDeleteResponsavel()">não</v-btn>
                        <v-btn color="#00897B" dark @click="deleteResponsavelConfirm()">sim</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogResponsaveladicionados" v-model="dialogResponsaveladicionados" max-width="500px">
                <v-card>
                    <v-card-text class="text-h5 py-3">
                        Todos os responsaveis já foram adicionados para o
                        projecto!
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="dialogResponsaveladicionados = false">Fechar</v-btn>

                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-if="dialogNaoExisteResponsavel" v-model="dialogNaoExisteResponsavel" max-width="500px">
                <v-card>
                    <v-card-text class="text-h5 py-3">
                        Não exite membros de equipe associado ao projecto desta
                        apartamento!
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="dialogNaoExisteResponsavel = false">Fechar</v-btn>

                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogPercentagem" v-model="dialogPercentagem" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5">Alterar a percentagem</v-card-title>
                    <v-form ref="formPercentagem" lazy-validation>
                        <v-card-text>
                            <v-text-field label="Alterar Percentagem" :rules="alterarPercentagemRules"
                                :aria-errormessage="erros.percentagem" v-model="apartamento.percentagem"
                                prepend-icon="percent" minlength="1" maxlegth="99" type="number" min="1" max="100">
                            </v-text-field>
                        </v-card-text>
                        <v-card-text>
                            <v-text-field label="Motivo da alteração da percentagem" :rules="motivoPercentagemRules"
                                :aria-errormessage="erros.percentagem" v-model="apartamento.designacao"
                                prepend-icon="description">
                            </v-text-field>
                        </v-card-text>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogRejeitarapartamento" v-model="dialogRejeitarapartamento" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5">Informe o motivo da rejeição</v-card-title>
                    <v-form ref="formRejeitarapartamento" lazy-validation>
                        <v-card-text>
                            <v-text-field label="Motivo da Rejeição da apartamento" :rules="motivoRegeicaoRules"
                                :aria-errormessage="erros.regeicao" v-model="apartamento.motivo_rejeicao"
                                prepend-icon="description">
                            </v-text-field>
                        </v-card-text>
                    </v-form>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color=" red " text @click="cancelarDialogRejeitarapartamento()">Cancel</v-btn>
                        <v-btn color="#00897B" text @click="saveRejeitarapartamento()">Adicionar</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogCancelaapartamento" v-model="dialogCancelaapartamento" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5">Informe o motivo do cancelamento</v-card-title>
                    <v-form ref="formCancelaapartamento" lazy-validation>
                        <v-card-text>
                            <v-text-field label="Motivo do Cancelamento da apartamento" :rules="motivocancelamentoRules"
                                :aria-errormessage="erros.regeicao" v-model="apartamento.motivo_cancelamento"
                                prepend-icon="description">
                            </v-text-field>
                        </v-card-text>
                    </v-form>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color=" red " text @click="cancelarDialogCancelaapartamento()">Cancel</v-btn>
                        <v-btn color="#00897B" text @click="saveCancelaapartamento()">Adicionar</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <!-- <v-row justify="center">
                <v-dialog v-model="dialogDetalheapartamento" fullscreen hide-overlay transition="dialog-bottom-transition">

                    <v-card>
                        <v-toolbar dark color="#00987b">
                            <v-btn icon dark @click="dialog = false">

                            </v-btn>
                        </v-toolbar>

                        <v-divider></v-divider>

                    </v-card>
                </v-dialog>
            </v-row> -->

            <v-row justify="center">
                <v-dialog v-if="dialogDetalheapartamento" v-model="dialogDetalheapartamento" fullscreen hide-overlay
                    transition="dialog-bottom-transition">
                    <v-card class="green lighten-5">
                        <v-toolbar class="text-h5 text-white corprincipal">
                            <v-spacer></v-spacer>
                            Detalhes da apartamento
                            <v-btn icon dark @click="dialogDetalheapartamento = false">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container>
                            <v-card-text>
                                <v-card class="ma-0">
                                    <v-subheader class="headertop font-weight-regular">
                                        <span style="font-weight: bolder">
                                            DADOS DA apartamento
                                        </span>
                                        <v-spacer></v-spacer>
                                        <span>
                                            <a :href="'/apartamentos/arquivo-pdf-apartamento/' +
                                                apartamento.id
                                                " target="__blank" class="remover-link">
                                                <v-icon title="Exportar em pdf" color="#fff">
                                                    mdi-file-export</v-icon>
                                                         </a> </span>
                                    </v-subheader>
                                    <v-row class="mx-auto text-left">
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Data de Inicio da
                                                    apartamento</strong><br />
                                                {{ apartamento.data_inicio_real }}
                                                <br /><br />
                                                <strong>Percentagem da
                                                    apartamento</strong><br />
                                                {{ apartamento.percentagem }}%
                                            </div>
                                        </v-col>
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Dias de Execução
                                                    apartamento</strong><br />
                                                {{ apartamento.tempo_execucao }}
                                                <br /><br />
                                                <strong>Estado da apartamento</strong><br />
                                                {{
                                                    apartamento.estado_apartamento
                                                        .designacao
                                                }}
                                            </div>
                                        </v-col>
                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Data Final da
                                                    apartamento</strong><br />
                                                {{ apartamento.data_fim_real }}
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
                                                    da apartamento
                                                </header>
                                            </v-expansion-panel-header>

                                            <v-expansion-panel-content>
                                                <v-row class="mx-auto">
                                                    <v-col cols="12" md="6">
                                                    </v-col>
                                                </v-row>

                                                <v-card-text class="text-justify white text--corprincipal"
                                                    v-html="apartamento.nome_apartamento">
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
                                                    apartamento.projecto.nome_proj
                                                }}
                                            </div>
                                        </v-col>

                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>
                                                    Prioridade do
                                                    Projecto:</strong><br /><br />
                                                {{
                                                    apartamento.projecto
                                                        .prioridade_proj
                                                }}
                                            </div>
                                        </v-col>

                                        <v-col cols="4">
                                            <div class="font-weight-normal">
                                                <strong>Estado do Projecto</strong><br /><br />
                                                {{
                                                    apartamento.projecto
                                                        .estado_projeto
                                                        .designacao
                                                }}
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card>
                                <v-card v-if="apartamento.control_apartamentos" class="">
                                    <v-subheader class="font-weight-regular headertop"><span
                                            style="font-weight: bolder">LISTA DE
                                            RESPONSAVEIS
                                        </span></v-subheader>
                                    <v-divider />

                                    <v-data-table :headers="headersResponsavelDetalhes"
                                        :items="apartamento.control_apartamentos" hide-default-footer>
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon small @click="deleteResponsavel(item)">
                                                mdi-delete
                                            </v-icon>
                                        </template>
                                        <template slot="no-data">
                                            sem nenhum dado
                                        </template>
                                        <template slot="no-results">
                                            não foi encontrado nehum dado na
                                            pesquisa
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-card-text>
                        </v-container>
                        <div style="flex: 1 1 auto"></div>
                    </v-card>
                </v-dialog>
            </v-row>
            <v-row>
                <v-col cols="12" md="4">
                    <v-btn bottom color="corprincipal" dark fab fixed right @click="carregarDialog()">
                        <v-icon>add </v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </div>
    </app-layout>
</template>

<script>
import { isExists } from "date-fns/esm";
import AppLayout from "../../Shared/AppLayout";
import TabApartamento from "../../components/TabApartamento";

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
        "tipos_apartamento",
        "todos_apartamentos",
        "apartamentos",
        "condominos",
        "estado_apartamentos",
        "control_apartamentos",
        "sindicos",
        'blocos',
        'condominos',
        'apartamentos'
    ],
    components: {
        AppLayout,
        TabApartamento,
    },

    data() {
        return {
            // A qui são declaradas as outras variaveisque serão usadas para manipular os dados quer o do banco de dados como as instancias recorrentes.
            responsaveisapartamentos: [],
            responsaveis_projeto: [],
            show3: "",
            show: "",
            show2: "",
            dialogResponsavel: false,
            dialogResponsaveladicionados: false,
            dialogNaoExisteResponsavel: false,
            dialogDeleteResponsavel: false,
            dialog: false,
            dialogDelete: false,
            dialogDetalheapartamento: false,
            dialogPercentagem: false,
            panel_motivo: "",
            dialogRejeitarapartamento: false,
            dialogCancelaapartamento: false,
            apartamento_responsavel: {
                responsavel_id: [],
            },
            idprojecto: "",
            apartamento: {

            },
            defaultapartamento: {

            },
            projecto_apartamento: {},
            query: {
            },
            editedIndex: -1,
            deletedIndex: -1,
            search: "",
            erros: [],
            nomeapartamentoRules: [(v) => !!v || "Campo Obrigatório"],
            descricacaoapartamentoRules: [
                (v) =>
                    !!v ||
                    "A descrição da apartamento tem de ser clara e sugestiva com no minimo de 20 caracter para consiguir se ter a ideia da apartamento",
                (v) =>
                    (v && v.length > 0) ||
                    "A descrição da apartamento é muito curta",
            ],
            dataFimRealRules: {
                Valido(value, data) {
                    return (
                        value > data ||
                        "A data de Final tem de ser maior que a data! dados"
                    );
                },
            },

            dataInicioRules: [
                (v) => !!v || "Data Obrigatório",
                (v) =>
                    v >= new Date().toISOString().substr(0, 10) ||
                    "Data Inicial Invalida!",
            ],

            dataInicialfiltroRules: [(v) => !!v || "Data Obrigatório"],

            dataFinalFiltrolRules: {
                Valido(value, data) {
                    return (
                        value >= data ||
                        "A data final não pode ser inferior que a data inicial"
                    );
                },
            },
            motivoPercentagemRules: [(v) => !!v || "Campo Obrigatório"],

            headers: [
                {
                    text: "Nº",
                    value: "descricao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nº do Bloco",
                    value: "designacao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nome do condominio",
                    value: "condomino.nome_pessoa",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                },

                {
                    text: "Estado",
                    value: "estado_apartamento.designacao",
                    class: "font-weight-bold black--text subtitle-1 my-3",
                    sortable: false,
                },
                {
                    text: "Data de ingresso",
                    value: "data_ingresso",
                    align: "center",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                    // color: "teal darken-1",
                },

                {
                    text: "Tipo de apartamento",
                    value: "tipo_apartamento.descricao",
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
        apartamentoPendente(item) {
            window.open("certificado-entrada/" + btoa(btoa(btoa(item.id))));
        },
        validate() {
            this.$refs.form.validate();
        },
        getColor(estado_apartamento_id) {
            if (estado_apartamento_id == 1) {
                return "orange";
            } else if (estado_apartamento_id == 2) {
                return "blue";
            } else if (estado_apartamento_id == 3) {
                return "orange";
            } else if (estado_apartamento_id == 4) {
                return "green";
            } else if (estado_apartamento_id == 5) {
                return "red";
            } else if (estado_apartamento_id == 6) {
                return "red";
            } else if (estado_apartamento_id == 7) {
                return "red";
            } else return "green";
        },
        aceitarapartamento() {
            this.$inertia.put(
                "/apartamentos/aceitar-apartamento/" + this.apartamento.id,
                this.apartamento,
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

        rejeitarapartamento(item) {
            this.apartamento = Object.assign({}, item);
            this.dialogRejeitarapartamento = true;
        },
        cancelarDialogRejeitarapartamento() {
            this.dialogRejeitarapartamento = false;
            this.$nextTick(() => {
                this.apartamento = Object.assign({}, this.defaultapartamento);
                this.editedIndex = -1;
            });
        },

        saveRejeitarapartamento() {
            if (this.$refs["formRejeitarapartamento"].validate()) {
                this.$inertia.put(
                    "/apartamentos/rejeitar-apartamento/" + this.apartamento.id,
                    this.apartamento,
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
                            this.cancelardialogRejeitarapartamento();
                        },
                    }
                );
            }
        },
        // Cancelar apartamentos

        cancelaapartamento(item) {
            this.apartamento = Object.assign({}, item);
            this.dialogCancelaapartamento = true;
        },
        cancelarDialogCancelaapartamento() {
            this.dialogCancelaapartamento = false;
            this.$nextTick(() => {
                this.apartamento = Object.assign({}, this.defaultapartamento);
            });
        },
     saveCancelaapartamento() {
            if (this.$refs["formCancelaapartamento"].validate()) {
                this.$inertia.put(
                    "/apartamentos/cancelamento-apartamento/" + this.apartamento.id,
                    this.apartamento,
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
                            this.cancelarDialogCancelaapartamento();
                        },
                    }
                );
            }
        },

        carregarDialog() {
            this.apartamento = Object.assign({}, this.defaultapartamento);
            this.editedIndex = -1;
            this.dialog = true;
        },

        editItem(item) {
            this.editedIndex = this.apartamentos.indexOf(item);
            this.apartamento = Object.assign({}, item);
            this.dialog = true;
        },
        verDetalhe(item) {
            this.apartamento = Object.assign({}, item);
            this.dialogDetalheapartamento = true;
        },
         deleteItem(item) {
            this.editedIndex = this.apartamentos.indexOf(item);
            this.apartamento = Object.assign({}, item);
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.apartamento = Object.assign({}, this.defaultapartamento);
                this.editedIndex = -1;
            });
        },

        deleteItemConfirm() {
            this.$inertia.delete("/apartamentos/apartamento/" + this.apartamento.id, {
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

        adicionarApartamentos() {
            this.filtrarProjectoResponsavel();
        },

        apartamentoConcluido(item) {
            // this.editedIndex = this.apartamentos.indexOf(item)
            this.apartamento = Object.assign({}, item);
            this.$inertia.put(
                "/apartamentos/apartamento-concluido/" + this.apartamento.id,
                this.apartamento,
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
                        this.cancelarDialogPercentagem();
                    },
                }
            );
        },
        aceitarapartamento(item) {
            // this.editedIndex = this.apartamentos.indexOf(item)
            this.apartamento = Object.assign({}, item);

            this.$inertia.put(
                "/apartamentos/aceitar-apartamento/" + this.apartamento.id,
                this.apartamento,
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
                        this.cancelarDialogPercentagem();
                    },
                }
            );
        },

        cancelarDialog() {
            this.apartamento = Object.assign({}, this.defaultapartamento);
            this.editedIndex = -1;
            this.dialog = false;
        },

        closeSave() {
            this.apartamento = Object.assign({}, this.defaultapartamento);
            this.editedIndex = -1;
            this.dialog = false;
        },

        save() {
            if (this.$refs["formapartamentos"].validate()) {
                if (this.editedIndex > -1) {
                    this.apartamento.projecto_id;

                    this.$inertia.put(
                        `/apartamentos/apartamento/${this.apartamento.id}`,
                        this.apartamento,
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
                                this.cancelarDialog();
                            },
                        }
                    );
                } else {
                    if (this.projecto_marcado) {
                        this.apartamento.projecto_id = this.projecto_marcado;
                    }
                    //  alert(JSON.stringify(this.apartamento))
                    this.$inertia.post("/apartamentos/apartamento", this.apartamento, {
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

  
        filtrarEstado() {
            if (this.projecto_marcado) {
                this.query.projecto_id = this.projecto_marcado;
            }
            axios
                .get("/apartamentos/filtrar-estado", {
                    params: this.query,
                })
                .then((response) => {
                    this.apartamentos = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
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
