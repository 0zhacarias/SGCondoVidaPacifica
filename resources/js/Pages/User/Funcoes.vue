<template>
    <app-layout>
        <v-row>
            <v-col cols-12 sm="6" md="4">
                <v-card elevation="0">
                    <h3 class="black--text text-left font-weight-bold pa-2">
                        FUNÇÕES DO SISTEMA
                    </h3>
                </v-card>
            </v-col>
        </v-row>

        <!-- {{funcoes}} -->
        <v-container>
            <v-card class="mt-4">
                <template>
                    <v-card-title>
                        <v-spacer class="" light></v-spacer>
                        <div class="">
                            <v-btn
                                bottom
                                color="#00897B"
                                dark
                                @click="carregarDialog()"
                            >
                                <v-icon title="Adicionar função"
                                    >mdi-plus</v-icon
                                >
                            </v-btn>
                            <v-btn
                                color="red"
                                dark
                                class=""
                                :href="'funcionario/meus-dados/'"
                                target="_blank"
                            >
                                <v-icon title="Export em pdf"
                                    >mdi-file-pdf-box</v-icon
                                >
                            </v-btn>
                            <!-- <v-btn color="green" dark class="" :href="'funcionario/meus-dados/'" target="_blank">
                <v-icon title="Export em excel">mdi-file-excel</v-icon>
              </v-btn> -->
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Pesquisar as funções"
                            single-line
                            hide-details
                            class="font-weight-bold"
                        >
                        </v-text-field>

                        <v-data-table
                            :headers="headers"
                            :items="funcoes"
                            :search="search"
                            sort-by="id"
                        >
                            <template v-slot:item.actions="{ item }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem(item)"
                                    color="blue"
                                >
                                    mdi-pencil
                                </v-icon>
                                <v-icon
                                    small
                                    @click="deleteItem(item)"
                                    color="red"
                                >
                                    mdi-delete
                                </v-icon>
                            </template>
                            <template slot="no-data">
                                sem nenhum dado
                            </template>
                            <template slot="no-results">
                                não foi encontrado nehum dado na pesquisa
                            </template>
                        </v-data-table>
                    </v-card-text>
                </template>
            </v-card>
        </v-container>
        -->
        <v-dialog v-if="dialogDelete" v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title class="h1"
                    >Tens a certeza que pretendes eliminar?</v-card-title
                >
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn rounded outlined color="red" dark @click="closeDelete()">não</v-btn>
                    <v-btn rounded outlined color="#00897B" dark @click="deleteItemConfirm()"
                        >sim</v-btn
                    >
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container class="my-5">
            <v-dialog
                v-if="dialog"
                v-model="dialog"
                max-width="500px"
                persistent
            >
                <v-card>
                    <v-toolbar
                        dark
                        elevation="2"
                        class="text-uppercase font-weight-bold"
                    >
                        <v-toolbar-title>Adicionar Função</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-form ref="form" lazy-validation>
                                    <v-text-field
                                        v-model="postfuncao.designacao"
                                        label="Adicionar designação da função "
                                        hide-details="auto"
                                        :rules="descricaofuncaoRuls"
                                        :error-messages="erros.descricao"
                                        prepend-icon="mdi-shape-plus"
                                    ></v-text-field>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn rounded outlined dark @click="cancelarDialog()">Cancelar</v-btn>
                        <v-btn rounded outlined dark @click="save()">{{
                            editedIndex > -1 ? "atualizar" : "adicionar"
                        }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- outro botão -->
        </v-container>
    </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";

const gradients = [
    ["#222"],
    ["#42b3f4"],
    ["red", "orange", "yellow"],
    ["purple", "violet"],
    ["#00c6ff", "#F0F", "#FF0"],
    ["#f72047", "#ffd200", "#1feaea"],
];
export default {
    props: ["funcoes"],
    components: {
        AppLayout,
    },

    data() {
        return {
            erros: [],
            postfuncao: {},
            defaultpostfuncao: {},
            dialogDelete: false,
            dialog: false,
            search: "",
            editedIndex: -1,

            descricaofuncaoRuls: [(v) => !!v || "Campo Obrigattorio"],

            headers: [
                // { text: 'Nº', value: 'id', class: "font-weight-bold black--text teal lighten-5 subtitle-1", sortable: true },
                {
                    text: "Nome da função",
                    value: "designacao",
                    align: "start",
                    class: "font-weight-bold black--text teal lighten-5 subtitle-1",
                },
                {
                    text: "Ação",
                    value: "actions",
                    sortable: false,
                    class: "font-weight-bold black--text teal lighten-5 subtitle-1",
                },
            ],
            //
        };
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    mounted() {
        setTimeout(() => {
            this.dialog = false;
        }, 7000);
    },

    created() {},

    methods: {
        validate() {
            this.validate();
        },

        carregarDialog() {
            this.postfuncao = Object.assign({}, this.defaultpostfuncao);
            this.editedIndex = -1;
            this.dialog = true;
        },
        editItem(item) {
            this.editedIndex = this.funcoes.indexOf(item);
            this.postfuncao = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            this.editedIndex = this.funcoes.indexOf(item);
            this.postfuncao = Object.assign({}, item);
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.postfuncao = Object.assign({}, this.defaultpostfuncao);
                this.editedIndex = -1;
            });
        },

        deleteItemConfirm() {
            this.$inertia.delete(
                "/tabela_de_apoio/funcoes/" + this.postfuncao.id,
                {
                    onFinish: () => {
                        Vue.toasted.global.defaultSuccess({
                            msg: "Operação realizada com sucesso",
                        });
                        this.closeDelete();
                    },
                }
            );
        },

        cancelarDialog() {
            this.postfuncao = Object.assign({}, this.defaultpostfuncao);
            this.editedIndex = -1;
            this.erros = [];
            this.dialog = false;
        },

        closeSave() {
            this.postfuncao = Object.assign({}, this.defaultpostfuncao);
            this.editedIndex = -1;
            this.dialog = false;
        },

        save() {
            if (this.$refs["form"].validate()) {
                if (this.editedIndex > -1) {
                    //rota agrupada onde o primeir é o prefixo e o segundo parametro é a rota do arquivo
                    this.$inertia.put(
                        `/tabela_de_apoio/funcoes/${this.postfuncao.id}`,
                        this.postfuncao,
                        {
                            onFinish: () => {
                                Vue.toasted.global.defaultSuccess({
                                    msg: "Operação realizada com sucesso",
                                });
                                this.closeSave();
                            },
                        }
                    );
                } else {
                    this.$inertia.post(
                        "/tabela_de_apoio/funcoes",
                        this.postfuncao,
                        {
                            onFinish: () => {
                                if (this.$page.props.flash.success != null) {
                                    Vue.toasted.global.defaultSuccess({
                                        msg: "Operação realizada com sucesso",
                                    });
                                }
                                if (this.$page.props.flash.error != null) {
                                    Vue.toasted.global.defaultError({
                                        msg: "Não foi possível realizar a operação com sucesso",
                                    });
                                }
                                this.closeSave();
                            },
                        }
                    );
                }
            }
        },
    },
};
</script>

<style>
@import "vuetify/dist/vuetify.min.css";

table {
    font-weight: bolder;
}

.corprincipal {
    background-color: #00897b !important;
}
</style>
