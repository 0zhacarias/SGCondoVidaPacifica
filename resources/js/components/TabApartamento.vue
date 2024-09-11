<template>
    <div>
        <v-container>
            <v-card>
                <v-toolbar class="cortab" flat prominent> </v-toolbar>
                <v-btn v-model="fab" dark fab bottom color="corprincipal" class="mt-n16 ml-10" @click="editedItem(item)"
                    right height="150" width="150">
                    <v-icon large title="Editar os dados">mdi mdi-home-city</v-icon>
                </v-btn>
                <span class="text-h5 mx-10">
                    Bloco(#) <br />


                </span>
                <v-toolbar flat>
                    <template v-slot:extension>
                        <v-tabs v-model="tabs" left>
                            <v-tab> Descrição </v-tab>
                            <v-tab> Sindico </v-tab>
                            <v-tab> Pagamentos </v-tab>
                        </v-tabs>
                    </template>
                </v-toolbar>
                <v-tabs-items v-model="tabs">
                    <v-tab-item>
                        <v-card flat class="pb-4 container">
                            <template>
                                <v-data-table :headers="headersBloco" :items="apartamentos">
                                    <template slot="no-data">
                                        sem nenhum dado
                                    </template>
                                    <template slot="no-results">
                                        não foi encontrado nehum dado na pesquisa
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <button text @click="editItem(item)">
                                            <v-icon color="blue" icon large class="mr-2" title="Editar">
                                                mdi-pencil
                                            </v-icon>
                                            Editar
                                        </button>
                                        <button text @click="deleteItem(item)">
                                            <v-icon color="red" large icon title="Apagar">
                                                mdi mdi-trash-can-outline
                                            </v-icon>
                                            Remover
                                        </button>

                                        <span> </span>
                                    </template>
                                </v-data-table>
                                <v-card-title>
                                    <v-spacer class="" vertical></v-spacer>
                                </v-card-title>

                                <v-card-text> </v-card-text>
                            </template>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat class="pb-4 container">
                            <template>
                                <v-data-table :headers="headersSindico" :items="sindicos" :search="search">
                                                                     
                                    <template v-slot:item.numero="{ index }">
                                        <tr>
                                            <td>{{ index + 1 }}</td>
                                        </tr>
                                    </template>
                                    <template slot="no-data">
                                        sem nenhum dado
                                    </template>
                                    <template slot="no-results">
                                        não foi encontrado nehum dado na
                                        pesquisa
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <button text @click="editItem(item)">
                                            <v-icon color="blue" icon large class="mr-2" title="Editar">
                                                mdi-pencil
                                            </v-icon>
                                            Editar
                                        </button>
                                        <button text @click="deleteItem(item)">
                                            <v-icon color="red" large icon title="Apagar">
                                                mdi mdi-trash-can-outline
                                            </v-icon>
                                            Remover
                                        </button>
                                        <span> </span>
                                    </template>
                                </v-data-table>
                                <v-card-title>
                                    <v-spacer class="" vertical></v-spacer>
                            </v-card-title>

                                <v-card-text> </v-card-text>
                            </template>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat class="pb-4 container">
                            <template>
                                <v-data-table :headers="headersPagamento" :items="tarefas" :search="search">
                                    <template v-slot:item="{ item }">
                                        <v-chip :color="getColor(item)
                                            " dark>
                                            {{ item }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <span>{{ item }}%</span>
                                    </template>
                                    <template v-slot:item="{ index }">
                                        <tr>
                                            <td>{{ index + 1 }}</td>
                                        </tr>
                                    </template>
                                    <template slot="no-data">
                                        sem nenhum dado
                                    </template>
                                    <template slot="no-results">
                                        não foi encontrado nehum dado na
                                        pesquisa
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <button text @click="editItem(item)">
                                            <v-icon color="blue" icon large class="mr-2" title="Editar" :disabled="item ==
                                                6 ||
                                                item ==
                                                7 ||
                                                item == 4
                                                ">
                                                mdi-pencil
                                            </v-icon>
                                            Editar
                                        </button>
                                        <button text @click="deleteItem(item)">
                                            <v-icon color="red" large icon title="Apagar" :disabled="!user.can[
                                                'Eliminar tarefas'
                                            ]
                                                ">
                                                mdi mdi-trash-can-outline
                                            </v-icon>
                                            Remover
                                        </button>
 <span> </span>
                                    </template>
                                </v-data-table>
                                <v-card-title>
                                    <v-spacer class="" vertical></v-spacer>
                                </v-card-title>

                                <v-card-text> </v-card-text>
                            </template>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
            </v-card>
        </v-container>
        <v-dialog
                v-if="dialogDelete"
                v-model="dialogDelete"
                max-width="500px"
            >
                <v-card>
                    <v-card-title class="text-h6"
                        >Tens a certeza que pretendes eliminar a
                        apartamento?</v-card-title
                    >
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn  outlined rounded color="red" dark @click="closeDelete">não</v-btn>
                        <v-btn rounded outlined color="#00897B" dark @click="deleteItemConfirm"
                            >sim</v-btn
                        >
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
    </div>
</template>
<script>
import { method } from 'lodash';

export default {
    props: [
        "apartamentos",
        "sindicos",
    ],
    components: {},

    data() {
        return {
            tabs: null,
            fab: false,
            dialog: false,
            dialogDelete: false,
            editedIndex: -1,

            deletedIndex: -1,
            headersBloco: [
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
            headersSindico: [
                {
                    text: "Nº",
                    value: "",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Descrição do Apartamento",
                    value: "apartamento.descricao",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nome do Sindico",
                    value: "nome_pessoa",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                },
                {
                    text: "Gênero",
                    value: "genero.designacao",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                },
/*                 {
                    text: "Estado Cívil",
                    value: "",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                }, */

                {
                    text: "Telefone",
                    value: "telefone_pessoa",
                    class: "font-weight-bold black--text subtitle-1 my-3",
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
            headersPagamento: [
                {
                    text: "Nº",
                    value: "numero",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nº do Apartamento",
                    value: "nome_tarefa",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nome do Sindico",
                    value: "projecto",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                },

                {
                    text: "Nº de AP_P",
                    value: "estadoTarefa",
                    class: "font-weight-bold black--text subtitle-1 my-3",
                    sortable: false,
                },
                {
                    text: "Mêses pago",
                    value: "tempo_execucao",
                    align: "center",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                    // color: "teal darken-1",
                },
                {
                    text: "Mêses por pagar",
                    value: "tempo_execucao",
                    align: "center",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                    // color: "teal darken-1",
                },

                {
                    text: "Total",
                    value: "percentagem",
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
        editItem(item) {

            this.editedIndex = this.apartamentos.indexOf(item);
            this.apartamento = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            this.dialogDelete = true;
            // alert(JSON.stringify(this.apartamentos))
            this.deletedIndex = this.apartamentos.indexOf(item);
            this.apartamento = Object.assign({}, item);
            
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
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.apartamento = Object.assign({}, this.defaultapartamento);
                this.editedIndex = -1;
            });
        },

    }
};
</script>
<style scoped>
.cortab {
    background-color: #05343f !important;
}

.corprincipal {
    background-color: #0e85a3 !important;
}
</style>
