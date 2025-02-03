<template>
    <app-layout>
        <div class="dashboard">

            <v-container>
                <v-card elevation="0" class="mb-10">
                    <v-row>
                        <v-col cols="6" sm="6" md="6">
                            <h3 class="font-weight-bold">Despesas</h3>
                        </v-col>
                        <v-col class="text-right">
                            <v-btn v-if="user.can['Adicionar Serviços']" color="corprincipal" title="cadastrar despesas" class="white--text font-weight-bold"
                                @click="carregarDialog()">Serviços
                            </v-btn>
                            <v-dialog v-if="dialogDespesas" v-model="dialogDespesas" width="500" persistent>
                                <v-card>
                                    <v-toolbar class="text-uppercase font-weight-bold" elevation="0">
                                        <v-toolbar-title>
                                            {{
                                                editedIndex == -1
                                                    ? "Adicionar Serviços"
                                                    : "Atualizar Serviços"
                                            }}
                                        </v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-icon color="red" @click="closeSave()">mdi-close</v-icon>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-card class="mb-2" flat>
                                            <v-form ref="formdespesa" lazy-validation>
                                                <v-row>
                                                    <v-col cols="7">
                                                        <v-text-field outlined dense v-model="despesa.designacao
                                                            " label="Designação do despesa" class="pb-2"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="5">
                                                        <v-text-field outlined dense v-model="despesa.preco" 
                                                       type="number" label="Valor " hide-details="auto" min="1" max="99999">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-flex top class="text-right"></v-flex>
                                                <v-spacer />
                                                <div class="text-right">
                                                    <v-btn color="teal" dark @click="saveDespesas()" v-if="
                                                        editedIndex ==
                                                        -1
                                                    ">Guardar</v-btn>
                                                    <v-btn color="teal" dark @click="saveDespesas()" v-if="
                                                        editedIndex > -1
                                                    ">Actualizar</v-btn>
                                                </div>
                                            </v-form>
                                        </v-card>
                                    </v-card-text>
                                </v-card>
                            </v-dialog>
                        </v-col>
                    </v-row>
                </v-card>

                <v-row>
                    <v-col cols="6" md="4">
                        <v-card class="elevation-0">
                            <v-col cols="12">
                                <v-autocomplete v-model="factura.servicos" :items="servicos_map" item-text="designacao"
                                    item-value="id" @input="Servicos(factura.servicos)" prepend-icon="" label="Serviços"
                                    outlined dense>
                                </v-autocomplete>
                            </v-col>
                        </v-card>
                    </v-col>
                    <v-col cols="6" md="8">
                        <v-card class="elevation-0">
                            <v-col cols="6" sm="12" md="12">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                        <thead>
                                            <tr>
                                                <th class="text-left">
                                                    Designação
                                                </th>
                                                <th class="text-left">
                                                    Preço
                                                </th>
                                                <th class="text-left">
                                                    Quantidade
                                                </th>
                                                <th class="text-left">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in servicos_selecionado" :key="item.designacao">
                                                <td>{{ item.designacao }}</td>
                                                <td>{{ (item.preco).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' }) }}</td>
                                                <td>
                                                    <v-text-field v-model="item.quantidade" type="number" min="0"
                                                        max="12" dense @keyup.enter="TotalGeral(servicos_selecionado)"
                                                        @input="TotalGeral(servicos_selecionado)">
                                                    </v-text-field>
                                                </td>
                                                <td v-if="item.quantidade">{{ (item.total_g=item.preco * item.quantidade).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
                                                    }}</td>
                                            </tr>


                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th class="text-left">Total </th>
                                                <th>preço:{{ total_preco }} </th>
                                                <th>Qty:{{ total_quantidade }} </th>
                                                <th>Geral:{{ total_geral }} </th>
                                            </tr>
                                        </thead>
                                    </template>

                                </v-simple-table>
                                <div class="text-end mt-2">
                                    <v-btn :disabled="!total_quantidade" color="#0e85a3" dense small outlined rounded
                                        class="mr-auto" right @click="DespesaServicos()">
                                        Saida
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-card>
                    </v-col>
                </v-row>
                <v-card class="elevation-0 mt-10">
                    <template>
                        <v-data-table :headers="headers" :items="despesas" :search="search">
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

                            </template>
                        </v-data-table>
                    </template>
                </v-card>

            </v-container>
        </div>
    </app-layout>
</template>

<script>
import { isExists } from "date-fns/esm";
import AppLayout from "../../Shared/AppLayout";
import TabApartamento from "../../components/TabApartamento";
import { sum } from "lodash";
import { reduce } from "lodash";
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
        "servicos",
        "despesas"
    ],
    components: {
        AppLayout,
        TabApartamento,
    },

    data() {
        return {
            // A qui são declaradas as outras variaveisque serão usadas para manipular os dados quer o do banco de dados como as instancias recorrentes.
            factura: {
            },
            despesa: {},
            defaultdespesa: {},
            dialogDespesas: false,
            servicos_selecionado: [],
            todos_servicos: [],
            query: {
                estado_tarefa_id: null,
                data_final: null,
                data_inicial: null,
                responsavel_id: null,
                tarefa_id: null,
            },
            verver: null,
            dadosResponsavel: {},
            editedIndex: -1,

            deletedIndex: -1,
            search: "",
            erros: [],
            // Front nao aceitar campo em branco.
            headers: [
                {
                    text: "Nº",
                    value: "",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Serviços",
                    value: "descricao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "valor",
                    value: "valor_a_pagar",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
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

            total_quantidade: 0,
            total_preco: 0,
            total_geral: 0,
            servicos_map: [],
        };
    },
    methods: {
        carregarDialog() {
            this.despesa = Object.assign({}, this.defaultdespesa);
            this.editIndex = -1;
            this.dialogDespesas = true;
        },
        closeSave() {
            this.dialogDespesas = Object.assign({}, this.defaultdespesa);
            this.editedIndex = -1;
            this.dialogDespesas = false;
        },
        saveDespesas() {


            if (this.$refs["formdespesa"].validate()) {
               
                    this.$inertia.post("/financas/crear_despesa", this.despesa, {
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
                            this.closeSave();
                        },
                    });
            }
        },

        Servicos(item) {
            let dados = this.servicos_map.find((eleem) => eleem.id == item)
            this.servicos_map=this.servicos_map.filter(elem => elem.id !=item)
            this.todos_servicos = this.servicos_selecionado.push(dados)


        },
        TotalGeral(total) {
            this.total_quantidade = total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.quantidade), 0)
            this.total_preco = (total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.preco), 0)).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
            this.total_geral =  (total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.total_g), 0)).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
            
        },
        DespesaServicos() {
            axios.post('/financas/despesas-saida', {
                servicos: this.servicos_selecionado,
            }).then((response) => {
                window.open('/relatorios/despesas/' + response.data.despesa_id)
                this.servicos_selecionado = []
                this.pagamento = []
                this.total_quantidade = 0
                this.total_preco = 0
                this.total_geral = 0
            })

        },
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        // $tempoecucao=internalValue
    },

    mounted() {
    },

    created() {

        this.servicos_map = this.servicos.map((ele) => ({
            id: ele.id,
            designacao: ele.designacao,
            quantidade: 0,
            preco: ele.preco,
            total_g: 1,
        }))
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
