a
<template>
    <app-layout>
        <div class="dashboard">
            <v-row>
                <v-col cols-12 sm="6" md="3">
                    <v-card elevation="0">
                        <h3
                            class="black--text font-weight-bold pa-2 text-upercase"
                        >
                            ESTADO CÍVIL
                        </h3>
                    </v-card>
                </v-col>
            </v-row>

            <v-container>
                <template v-if="novoEstado">
                    <v-card class="mb-15 pa-0">
                        <v-toolbar
                            elevation="2"
                            class="text-uppercase font-weight-bold"
                        >
                            <v-toolbar-title
                                >Adicionar Estado Cívil</v-toolbar-title
                            >
                        </v-toolbar>
                        <v-form ref="form" lazy-validation>
                            <v-card-text>
                                <v-text-field
                                    v-model="estadocivil.designacao"
                                    label="Adicionar Estados"
                                    hide-details="auto"
                                    :rules="descricaofuncaoRuls"
                                    :error-messages="erros.descricao"
                                    prepend-icon="swipe_left_alt"
                                >
                                </v-text-field>
                            </v-card-text>
                        </v-form>
                        <v-card-actions class="justify-end">
                            <v-btn dark color="red" @click="closeSave()"
                                >Cancelar</v-btn
                            >
                            <v-btn dark color="#00897B" @click="save()">{{
                                editedIndex > -1 ? "atualizar" : "adicionar"
                            }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </template>
                <v-card>
                    <template>
                        <v-card-title>
                            <v-col md="2">
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Pesquisar o estado"
                                    single-line
                                    hide-details
                                    class="text-green"
                                >
                                </v-text-field>
                            </v-col>
                            <v-spacer></v-spacer>
                            <v-divider inset vertical></v-divider>
                            <v-btn
                                right
                                dark
                                color="#00897B"
                                @click="estado()"
                                v-if="abrirestado"
                            >
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-data-table
                                :headers="headers"
                                :items="estadoscivis"
                                :search="search"
                                sort-by="id"
                            >
                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        small
                                        class="mr-2 blue--text"
                                        @click="editItem(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        small
                                        class="red--text"
                                        @click="deleteItem(item)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </template>
                </v-card>
            </v-container>
            <v-dialog
                v-if="dialogDelete"
                v-model="dialogDelete"
                max-width="500px"
            >
                <v-card>
                    <v-card-title class="h1"
                        >Tens a certeza que pretendes eliminar?</v-card-title
                    >
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDelete"
                            >não</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="deleteItemConfirm"
                            >sim</v-btn
                        >
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";
export default {
    props: ["estadoscivis"],
    components: {
        AppLayout,
    },

    data() {
        return {
            erros: [],
            estadocivil: {},
            defaultestadocivil: {},
            dialogDelete: false,
            dialog: false,
            search: "",
            editedIndex: -1,
            novoEstado: false,
            abrirestado: true,

            descricaofuncaoRuls: [(v) => !!v || "Campo Obrigattorio"],

            headers: [
                // { text: 'Nº', value: 'id', class: "font-weight-blod top " },
                {
                    text: "Nome do estado da tarefa",
                    value: "designacao",
                    align: "start",
                    sortable: true,
                    class: "font-weight-blod",
                },
                {
                    text: "Ação",
                    value: "actions",
                    sortable: false,
                    class: "font-weight-blod ",
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
        estado() {
            this.novoEstado = true;
            this.abrirestado = false;
        },

        // carregarDialog() {
        //   this.estadocivil = Object.assign({}, this.defaultestadocivil);
        //   this.editedIndex = -1;
        //   this.dialog = true;
        // },
        editItem(item) {
            this.editedIndex = this.estadoscivis.indexOf(item);
            this.estadocivil = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            this.editedIndex = this.estadoscivis.indexOf(item);
            this.estadocivil = Object.assign({}, item);
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.estadocivil = Object.assign({}, this.defaultestadocivil);
                this.editedIndex = -1;
            });
        },

        deleteItemConfirm() {
            this.$inertia.delete(
                "/tabela_de_apoio/estado_civil/" + this.estadocivil.id,
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

        // cancelarDialog() {
        //   this.estadocivil = Object.assign({}, this.defaultestadocivil);
        //   this.editedIndex = -1;
        //    this.erros = [];
        //   this.dialog = false;

        // },

        closeSave() {
            this.estadocivil = Object.assign({}, this.defaultestadocivil);
            this.editedIndex = -1;
            this.erros = [];
            this.novoEstado = false;
            this.abrirestado = true;
        },

        save() {
            if (this.$refs["form"].validate()) {
                if (this.editedIndex > -1) {
                    //rota agrupada onde o primeir é o prefixo e o segundo parametro é a rota do arquivo
                    this.$inertia.put(
                        `/tabela_de_apoio/estado_civil/${this.estadocivil.id}`,
                        this.estadocivil,
                        {
                            onFinish: () => {
                                Vue.toasted.global.defaultSuccess({
                                    msg: "Operação realizada com sucesso",
                                });
                                this.editedIndex = -1;
                                this.erros = [];
                            },
                        }
                    );
                } else {
                    this.$inertia.post(
                        "/tabela_de_apoio/estado_civil",
                        this.estadocivil,
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

.primarytop {
    background-color: #122c1e !important;
}
</style>
