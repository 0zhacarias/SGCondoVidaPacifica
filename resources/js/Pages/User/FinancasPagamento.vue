<template>
    <app-layout>
        <div class="dashboard">
            <v-card elevation="0">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">Pagamento ()</h3>
                    </v-col>
                </v-row>
            </v-card>
                    <v-card class="mt-10">
                        <v-toolbar color="cortab" dark flat>
                            <template v-slot:extension>
                                <v-tabs v-model="tabs" centered>
                                    <v-tab v-for="servico in pagamentos" :key="servico">
                                        {{ servico.mes }}
                                    </v-tab>
                                </v-tabs>
                            </template>
                        </v-toolbar>

                        <v-tabs-items v-model="tabs">
                            <v-tab-item v-for="item in pagamentos" :key="tab">
                                <v-card flat >
                                    <v-card-text>
                                        <v-row class="m-0 p-0">
                                            <v-col class="p-1" v-for="servico in item.servicos" :key="servico"
                                                :md="item.servicos.length > 4 ? 2 : 3">
                                                <v-card :color="servico.created_by==user.id? 'green' : 'red'">
                                                    <v-card-text>
                                                      <h4>Serviços :{{servico.created_by==user.id?"Pago" :"Não pago"}}</h4>  {{ servico.descricao }}
                                                    </v-card-text>
                                                </v-card>
                                            </v-col>
                                        </v-row> </v-card-text>
                                </v-card>
                            </v-tab-item>

                        </v-tabs-items>
                    </v-card>
                <v-card class="elevation-0 mb-12 py-4
                ">
 </v-card>

        </div>
    </app-layout>
</template>

<script>

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
        "msg",
        "tarefas",
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
            tabs: null,
            pagamentos: [],
            servicos: [],
        };
    },
    methods: {
        Despesas() {
            axios
                .get("/financas/despesas", {})
                .then((response) => {
                    this.pagamentos = response.data.items;
                    this.servicos = response.data.servicos;
            
                })
                .catch((error) => {
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
        this.Despesas()
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
    background-color: #0e85a3 !important;
}

.headertop {
    color: #ffffff !important;
    background-color: #00897b !important;
    font-size: 1.1rem !important;
    font-weight: bold;
}

.v-data-table header {
    font-size: 1px;
}

.v-data-table th {
    font-size: 9px;
}

.v-data-table td {
    font-size: 14px !important;
}
</style>
