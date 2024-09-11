<template>
    <app-layout>
        <div class="dashboard">
            <v-card elevation="0" class="my-5">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">Relatorios ()</h3>
                    </v-col>
                </v-row>
            </v-card>
            <v-container>
                <v-card class="my-5 elevation-0">
                <div class="text-h5 mx-2 font-weight-bold" style="color: #0a6e84">
                    Filtros <span class="mdi mdi-filter-outline"></span>
                </div>
                <v-card-title>
                    <v-row class="mx-2">
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Bloco</label>

                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query.bloco" item-value="id"
                                item-text="descricao_bloco" type="text" :items="blocos" outlined clearable dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Condomino</label>
                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query.condomino" item-value="id"
                              :items="condominos"  item-text="nome_pessoa" type="text" outlined clearable dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Apartamentos</label>
                            <v-autocomplete @change="filtrarEstado()" clearable v-model="query.apartamento" item-text="designacao"
                               :items="apartamentos" item-value="id" prepend-icon="" outlined dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for=""></label>
                            <v-text-field @input="filtrarData()" label="Data de Criação" v-model="query.data_criacao" type="date"
                                outlined dense>
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-card-title>
            </v-card>
                <v-row>
                        <v-col v-for="item in items" :key="item" :md="item.length>4 ? 2:3 ">
                          
                            <v-card @click="imprimirRelatorio(item)">
                               <v-card-title>
                                   <v-spacer class="" vertical>{{ item.title }}</v-spacer>
                                </v-card-title>
                            </v-card>
                        </v-col>
                    </v-row>
            </v-container>

        </div>
    </app-layout>
</template>

<script>

import axios from "axios";
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
            query:{},
            // A qui são declaradas as outras variaveisque serão usadas para manipular os dados quer o do banco de dados como as instancias recorrentes.
             items: [
               {
                 title: "Relatório de Inadeplência",
                 route:"/inadeplencia"
               },
               {
                 title: "Balancete",
                  route:"/balancete"
               },
               {
                 title: "Livro de caixa",
                  route:"/caixa"
               },
               {
                 title: "Click Me 2",
                  route:"/click"
               },
             ],
           

            headers: [
                {
                    text: "Nº",
                    value: "numero",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nº do Bloco",
                    value: "nome_tarefa",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                },
                {
                    text: "Nome do Sindico",
                    value: "projecto.nome_proj",
                    class: "font-weight-bold  black--text subtitle-1 my-3",
                    sortable: false,
                },

                {
                    text: "Estado",
                    value: "estadoTarefa",
                    class: "font-weight-bold black--text subtitle-1 my-3",
                    sortable: false,
                },
                {
                    text: "Prazo para pagar (dias)",
                    value: "tempo_execucao",
                    align: "center",
                    class: "font-weight-bold black--text subtitle-1 my-3 ",
                    sortable: false,
                    // color: "teal darken-1",
                },

                {
                    text: "Percentagem %",
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
        
        imprimirRelatorio(item){
           // alert(JSON.stringify(`${item.route}/${this.query.bloco}`))
         /*   window.open(`/relatorios${item.route}`,{
                params:this.query
            }); */
           axios.get(`/relatorios${item.route}`,{
                params:this.query
            }) 
        },
   
        filtrarEstado() {
            if (this.projecto_marcado) {
                this.query.projecto_id = this.projecto_marcado;
            }
            axios
                .get("/tarefas/filtrar-estado", {
                    params: this.query,
                })
                .then((response) => {
                    this.tarefas = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        // Este metodos serve para filtrar todos os membros de alguma tarefa!
        filtrarResponsavelTarefa() {
            axios
                .get("/tarefas/filtrar-responsavel-tarefa", {
                    params: this.tarefa_responsavel,
                })
                .then((response) => {
                    this.responsaveisTarefas = response.data.responsaveis;
                    let exite = response.data.existe_responsaveis;
                    if (exite == 1) {
                        if (this.responsaveisTarefas.length > 0) {
                            this.dialogResponsavel = true;
                        } else {
                            this.dialogResponsaveladicionados = true;
                        }
                    } else {
                        this.dialogNaoExisteResponsavel = true;
                    }
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
        // $tempoecucao=internalValue
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

</style>
