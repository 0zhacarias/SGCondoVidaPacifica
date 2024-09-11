<template>
    <app-layout>
        <div class="dashboard">
            <v-row dense>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                            :href="'/tarefas/listar-tarefa/' + tarefa(1)"
                            class="color-text-link remover-link"
                        >
                            <v-card-title
                                id="atualizacao"
                                class="center"
                                align="center"
                            >
                                TAREFAS PENDENTES</v-card-title
                            >
                            <v-card-text id="atualizacaoPendente">
                                <h1 align="center">
                                    {{ contarTarefasPendente }}
                                </h1>
                                <v-divider></v-divider>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                            :href="'/tarefas/listar-tarefa/' + tarefa(2)"
                            class="color-text-link remover-link"
                        >
                            <v-card-title id="atualizacao" class="center"
                                >TAREFAS EM ANDAMENTO</v-card-title
                            >
                            <v-card-text id="atualizacaoAndamento">
                                <h1 align="center">
                                    {{ contarTarefasAndamento }}
                                </h1>
                                <v-divider></v-divider>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                            :href="'/tarefas/listar-tarefa/' + tarefa(3)"
                            class="color-text-link remover-link"
                        >
                            <v-card-title id="atualizacao" class="center"
                                >TAREFAS EM AVALIAÇÃO</v-card-title
                            >
                            <v-card-text id="atualizacaoEmAvaliacacao">
                                <h1 align="center">
                                    {{ contarTarefasaviliacao }}
                                </h1>
                                <v-divider></v-divider>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-1" color="white">
                        <inertia-link
                            :href="'/tarefas/listar-tarefa/' + tarefa(4)"
                            class="color-text-link remover-link"
                        >
                            <v-card-title id="atualizacao" align="center"
                                >TAREFAS FINALIZADAS</v-card-title
                            >
                            <v-card-text id="atualizacaoFinalizado">
                                <h1 sm="12" align="center">
                                    {{ contarTarefasfinalizada }}
                                </h1>
                                <v-divider></v-divider>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="10">
                    <!-- <template>
            <ColumnChart :xAxis="chart.xAxis" :series="chart.series" />
            </template> -->
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="12" md="8">
                    <!-- <v-card elevation="3">
            <v-subheader class="font-weight-regular corprincipal white--text mt-4">
              <span style="font-weight: bolder">
                <h2>NÍVEL DE DESEMPEIO SEMANAL </h2>
              </span>
            </v-subheader>
          </v-card> -->

                    <v-card
                        class="elevation-0 font-weight-regular black--text"
                        style="font-weight: bolder"
                    >
                        <v-subheader
                            class="font-weight-bolder my-5 pts-10 text-uppercase"
                        >
                            <v-row>
                                <v-col cols="12" md="10">
                                    <span style="font-weight: bolder"
                                        >Níveis de desempenho dos membros por
                                        tarefas!</span
                                    >
                                </v-col>
                            </v-row>
                        </v-subheader>

                        <v-card>
                            <template elevation="4">
                                <v-card>
                                    <v-tabs
                                        color="teal"
                                        class="font-weight-bolder"
                                    >
                                        <v-tab> Pendentes </v-tab>
                                        <v-tab>
                                            <v-icon left> </v-icon>
                                            Finalizados
                                        </v-tab>
                                        <v-tab-item>
                                            <v-card flat class="ma-5">
                                                <column-chart
                                                    :colors="['orangered']"
                                                    height="300px"
                                                    :data="
                                                        membros_quantidade_tarefa_pendente
                                                    "
                                                ></column-chart>
                                            </v-card>
                                        </v-tab-item>
                                        <v-tab-item>
                                            <v-card flat class="ma-5">
                                                <v-card-text>
                                                    <column-chart
                                                        :colors="['#008B8B']"
                                                        height="220px"
                                                        :data="
                                                            membros_quantidade_tarefa_finalizado
                                                        "
                                                    ></column-chart>
                                                    <p class="my-1"></p>
                                                </v-card-text>
                                            </v-card>
                                        </v-tab-item>
                                    </v-tabs>
                                </v-card>
                            </template>
                        </v-card>
                    </v-card>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card elevation="0" class="mt-5">
                        <v-subheader class="font-weight-bolders">
                            <span style="font-weight: bolder">
                                TAREFAS COM MAIORES PERCENTAGEMS
                            </span>
                        </v-subheader>
                        <v-divider></v-divider>
                        <template>
                            <v-timeline align-top dense>
                                <v-timeline-item
                                    color="#076461"
                                    small
                                    v-for="tarefa in tarefas"
                                    :key="tarefa.id"
                                >
                                    <v-row class="pt-1">
                                        <v-col cols="3">
                                            <strong
                                                >{{
                                                    tarefa.percentagem
                                                }}%</strong
                                            >
                                        </v-col>
                                        <v-col>
                                            <strong>{{
                                                tarefa.nome_tarefa
                                            }}</strong>
                                            <!-- <div class="text-caption">
                                                Mobile App
                                            </div> -->
                                        </v-col>
                                    </v-row>
                                </v-timeline-item>
                            </v-timeline>
                        </template>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";
// import Chart from "chart.js/auto";
// import { ColumnChart } from 'vuejs-highcharts'

export default {
    // name: 'BasicChartSample',
    props: [],
    components: {
        AppLayout,
        // ColumnChart
    },
    data() {
        return {
            query: {},
            tarefas: {},
            projetos: 0,
            contarTarefasPendente: 0,
            contarTarefasAndamento: 0,
            contarTarefasfinalizada: 0,
            contarTarefasaviliacao: 0,
            contarTarefasatrazada: 0,
            projetoestado: 0,
            tarefaestado: null,
            width: 2,
            elevation: 4,
            radius: 10,
            padding: 8,

            autoLineWidth: false,
            tarefa_andamento: ["0"],
            membros_quantidade_tarefa_finalizado: [],
            membros_quantidade_tarefa_pendente: [],
            filtro: ["0"],
            nome_responsavel_tarefas_andamento: ["0"],
            colecao: ["0"],

            // chart: {
            //   xAxis: {
            //     categories: this. nome_responsavel_tarefas_andamento
            //   },
            //   series: [
            //     {
            //       name: 'Installation',
            //       color: '#03bbd4',
            //       data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]

            //     },
            //     {
            //       name: 'Manufacturing',
            //       color: '#f7a35c',
            //       data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
            //     }
            //   ]
            // }
        };
    },

    created() {
        this.renderizarDadosDashboard();
        this.estadoFinalizado();
        // alert(1);
    },

    methods: {
        renderizarDadosDashboard() {
            // alert(JSON.stringify('ola'));
            axios
                .get("/dashboard")
                .then((response) => {
                    // alert(JSON.stringify(response.data))
                    this.contarTarefasPendente =
                        response.data.contarTarefasPendente;
                    this.contarTarefasAndamento =
                        response.data.contarTarefasAndamento;
                    this.contarTarefasfinalizada =
                        response.data.contarTarefasfinalizada;
                    this.contarTarefasaviliacao =
                        response.data.contarTarefasaviliacao;
                    this.contarTarefasatrazada =
                        response.data.contarTarefasatrazada;
                    this.tarefas = response.data.tarefas;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },

        // metodo que cria o link dos card para dar acesso as tarefas e criptografar o caminho
        tarefa(item) {
            // window.open("/tarefas/listar-tarefa/" + btoa(btoa(btoa(item))));
            return btoa(btoa(btoa(item)));
        },
        estadoFinalizado() {
            axios
                .get("/grafico-responsaveis-tarefa-dashboard")
                .then((response) => {
                    this.filtro = response.data.tarefa_finalizado;
                    this.membros_quantidade_tarefa_pendente =
                        response.data.membros_quantidade_tarefa_pendente;
                    this.membros_quantidade_tarefa_finalizado =
                        response.data.membros_quantidade_tarefa_finalizado;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },

        estadoPendente() {
            axios
                .get("/estatistica-grafico-pendente")
                .then((response) => {
                    this.filtro = response.data.tarefa_pendente;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        estatisticaDashboard() {
            axios
                .get("/projectos/estatistica-grafico-dashboard")
                .then((response) => {
                    this.filtro = response.data.tarefa_pendente;
                    this.nome_responsavel_tarefas_andamento =
                        response.data.nome_responsavel_tarefas_andamento;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
    },

    mounted() {
        this.estatisticaDashboard();
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },
};
</script>

<style scoped>
.v-sheet--offset {
    top: -4px;
    position: relative;
}

#atualizacao {
    color: #000;
    font-weight: 520;
    font-size: 0.8rem;
    text-align: center;
}
#atualizacaoPendente {
    color: #aa1111 !important;
}

#atualizacaoAndamento {
    color: #03a9f4;
}

#atualizacaoEmAvaliacacao {
    color: #f06819;
}

#atualizacaoFinalizado {
    color: #4caf50 !important;
}

.v-divider {
    background-color: #ffffff;
}

.corprincipal {
    /* background-color:#125e35 !important; */
    background-color: #00897b;
}

.remover-link {
    text-decoration: none !important;
}

.remover-link:visited {
    color: black;
}
</style>
