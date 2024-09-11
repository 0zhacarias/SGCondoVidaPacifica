<template>
    <app-layout>
        <div class="dashboard">

            <v-container>
                <v-card elevation="0" class="mb-10 p-2">
                    <v-row>
                        <v-col cols="6" sm="6" md="6">
                            <h3 class="font-weight-bold">Factura</h3>
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
                                                <td>{{ item.preco }}</td>
                                                <td>
                                                    <v-text-field v-model="item.quantidade" type="number" min="0"
                                                        max="12" dense @keyup.enter="TotalGeral(servicos_selecionado)"
                                                        @input="TotalGeral(servicos_selecionado)">
                                                    </v-text-field>
                                                </td>
                                                <td v-if="item.quantidade">{{ item.total_g=item.preco * item.quantidade
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
                                        class="mr-auto" right @click="EmitirFatura()">
                                        Emitir factura
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-card>
                    </v-col>
                </v-row>

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
        "projetos",
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
            // A qui são declaradas as outras variaveisque serão usadas para manipular os dados quer o do banco de dados como as instancias recorrentes.
            responsaveisTarefas: [],
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
            dialogDetalheTarefa: false,
            dialogPercentagem: false,
            panel_motivo: "",
            dialogRejeitarTarefa: false,
            dialogCancelaTarefa: false,
            tarefa_responsavel: {
                responsavel_id: [],
            },
            idprojecto: "",
            factura: {
            },
            defaultpagamento: {
                data_inicio_real: " ",
                data_fim_real: "",
                tempo_execucao: "",
                nome_tarefa: "",
                responsavel_id: [],
            },
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
                    text: 'Dessert (100g serving)',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'Calories', value: 'calories' },
            ],
            desserts: [
                {
                    name: 'Frozen Yogurt',
                    calories: 159,
                    fat: 6.0,
                    carbs: 24,
                    protein: 4.0,
                    iron: 1,
                },
                {
                    name: 'Ice cream sandwich',
                    calories: 237,
                    fat: 9.0,
                    carbs: 37,
                    protein: 4.3,
                    iron: 1,
                },
                {
                    name: 'Lollipop',
                    calories: 392,
                    fat: 0.2,
                    carbs: 98,
                    protein: 0,
                    iron: 2,
                },
            ],

            nomeTarefaRules: [(v) => !!v || "Campo Obrigatório"],
            descricacaoTarefaRules: [
                (v) =>
                    !!v ||
                    "A descrição da tarefa tem de ser clara e sugestiva com no minimo de 20 caracter para consiguir se ter a ideia da tarefa",
                (v) =>
                    (v && v.length > 0) ||
                    "A descrição da tarefa é muito curta",
            ],
            tempoExecucaoTarefaRules: [
                (v) => !!v || "O tempo da tarefa está vazio",
                // v => v.length <=3 || 'O campo esta vazio',
                (v) => v > 0 || "Não pode ser menor que 1 dia",
            ],
            referencaProjetoRules: [(v) => !!v || "Campo Obrigatório"],
            referencaResponsavelRules: [(v) => !!v || "Campo Obrigatório"],

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
            //Validar Responsavel
            alterarPercentagemRules: [
                (v) => !!v || "Data Obrigatório",
                (v) =>
                    v <= 100 || "A quantidade não pode ser superior que 100%",
            ],
            motivoPercentagemRules: [(v) => !!v || "Campo Obrigatório"],
            AdicionaroResponsavelRules: [(v) => !!v || "Campo Obrigatório"],
            motivoRegeicaoRules: [(v) => !!v || "Campo Obrigatório!"],
            motivocancelamentoRules: [(v) => !!v || "Campo Obrigatório!"],

            responsavelRules: [(v) => !!v || "Capmo Obrigatorio"],

            total_quantidade: 0,
            total_preco: 0,
            total_geral: 0,
            servicos_map: [],
        };
    },
    methods: {
        Servicos(item) {
            let dados = this.servicos_map.find((eleem) => eleem.id == item)
            this.todos_servicos = this.servicos_selecionado.push(dados)


        },
        TotalGeral(total) {
            this.total_quantidade = total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.quantidade), 0)
            this.total_preco = total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.preco), 0)
            this.total_geral = total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.total_g), 0)
            /*   .reduce((a, b) => a + b, 0)  */
            // alert(JSON.stringify(this.total_geral))
            /*  alert(JSON.stringify( total.map(({total_g}) => total_g)
            .reduce((a, b) => a + b, 0)))  */
        },
        EmitirFatura() {
            axios.post('/financas/emitir-factura', {
                servicos: this.servicos_selecionado,
            }).then((response) => {
                window.open('/relatorios/factura/' + response.data.factura_id)
                this.servicos_selecionado = []
                this.pagamento = []
                this.total_quantidade = 0
                this.total_preco = 0
                this.total_geral = 0
            })

        },
        tarefaPendente(item) {
            window.open("certificado-entrada/" + btoa(btoa(btoa(item.id))));
        },
        getColor(estado_tarefa_id) {
            if (estado_tarefa_id == 1) {
                return "orange";
            } else if (estado_tarefa_id == 2) {
                return "blue";
            } else if (estado_tarefa_id == 3) {
                return "orange";
            } else if (estado_tarefa_id == 4) {
                return "green";
            } else if (estado_tarefa_id == 5) {
                return "red";
            } else if (estado_tarefa_id == 6) {
                return "red";
            } else if (estado_tarefa_id == 7) {
                return "red";
            } else return "green";
        },
        aceitarTarefa() {
            this.$inertia.put(
                "/tarefas/aceitar-tarefa/" + this.pagamento.id,
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

        rejeitarTarefa(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogRejeitarTarefa = true;
        },
        cancelarDialogRejeitarTarefa() {
            this.dialogRejeitarTarefa = false;
            this.$nextTick(() => {
                this.pagamento = Object.assign({}, this.defaultpagamento);
                this.editedIndex = -1;
            });
        },

        saveRejeitarTarefa() {
            if (this.$refs["formRejeitarTarefa"].validate()) {
                this.$inertia.put(
                    "/tarefas/rejeitar-tarefa/" + this.pagamento.id,
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
                            this.cancelardialogRejeitarTarefa();
                        },
                    }
                );
            }
        },
        // Cancelar tarefas

        cancelaTarefa(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogCancelaTarefa = true;
        },
        cancelarDialogCancelaTarefa() {
            this.dialogCancelaTarefa = false;
            this.$nextTick(() => {
                this.pagamento = Object.assign({}, this.defaultpagamento);
            });
        },

        saveCancelaTarefa() {
            if (this.$refs["formCancelaTarefa"].validate()) {
                this.$inertia.put(
                    "/tarefas/cancelamento-tarefa/" + this.pagamento.id,
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
                            this.cancelarDialogCancelaTarefa();
                        },
                    }
                );
            }
        },

        carregarDialog() {
            this.pagamento = Object.assign({}, this.defaultpagamento);
            this.editedIndex = -1;
            this.dialog = true;
        },

        editItem(item) {
            //  this.idprojecto=item.projecto_id;
            this.filtrarProjectoResponsavel(item.projecto_id);
            // this.adicionarResponsavelProjecto(item.projecto_id);
            //  alert(JSON.stringify(this.idprojecto))
            this.editedIndex = this.tarefas.indexOf(item);
            this.pagamento = Object.assign({}, item);
            this.dialog = true;
        },
        verDetalhe(item) {
            this.pagamento = Object.assign({}, item);
            this.dialogDetalheTarefa = true;
        },


        deleteItem(item) {
            this.editedIndex = this.tarefas.indexOf(item);
            this.pagamento = Object.assign({}, item);
            this.dialogDelete = true;
        },


        deleteItemConfirm() {
            this.$inertia.delete("/tarefas/tarefa/" + this.pagamento.id, {
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

        tarefaConcluido(item) {
            // this.editedIndex = this.tarefas.indexOf(item)
            this.pagamento = Object.assign({}, item);
            this.$inertia.put(
                "/tarefas/tarefa-concluido/" + this.pagamento.id,
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
                        this.cancelarDialogPercentagem();
                    },
                }
            );
        },
        aceitarTarefa(item) {
            // this.editedIndex = this.tarefas.indexOf(item)
            this.pagamento = Object.assign({}, item);

            this.$inertia.put(
                "/tarefas/aceitar-tarefa/" + this.pagamento.id,
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
                        this.cancelarDialogPercentagem();
                    },
                }
            );
        },

        cancelarDialog() {
            this.pagamento = Object.assign({}, this.defaultpagamento);
            this.editedIndex = -1;
            this.dialog = false;
        },

        closeSave() {
            this.pagamento = Object.assign({}, this.defaultpagamento);
            this.editedIndex = -1;
            this.dialog = false;
        },

        save() {
            if (this.$refs["formTarefas"].validate()) {
                if (this.editedIndex > -1) {
                    this.pagamento.projecto_id;

                    this.$inertia.put(
                        `/tarefas/tarefa/${this.pagamento.id}`,
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
                                this.cancelarDialog();
                            },
                        }
                    );
                } else {
                    if (this.projecto_marcado) {
                        this.pagamento.projecto_id = this.projecto_marcado;
                    }
                    //  alert(JSON.stringify(this.pagamento))
                    this.$inertia.post("/tarefas/tarefa", this.pagamento, {
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
        // $tempoecucao=internalValue
    },

    mounted() {
        //if(this.projecto_marcado){
        // alert(JSON.stringify(projecto_marcado))
        //}
        // alert(JSON.stringify(projetos))
    },

    created() {

        this.servicos_map = this.servicos.map((ele) => ({
            id: ele.id,
            designacao: ele.designacao,
            quantidade: 0,
            preco: ele.preco,
            total_g: 1,
        }))
        if (this.projecto_marcado) {
            this.pagamento.projecto_id = this.projecto_marcado;
            this.filtrarProjectoResponsavel();
        }
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
