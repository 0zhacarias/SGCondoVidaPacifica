<template>
    <app-layout>
        <div class="dashboard">
            <v-row dense>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                            class="color-text-link remover-link"
                        >
                            <v-card-title
                                id="atualizacao"
                                class="center"
                                align="center"
                            >
                                <v-icon large color="#0E85A3">
                                    mdi-arrow-up-bold-box-outline
                                </v-icon>
                            </v-card-title>
                            <v-card-text>
                         

                                <p>Total de valores Pago</p>
                                <h4 align="center" id="atualizacaoAndamento">{{ valorPago }}</h4>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                       
                            class="color-text-link remover-link"
                        >
                        <v-card-title
                                id="atualizacao"
                                class="center"
                                align="center"
                            >
                                <v-icon large color="#EA5F46">
                                    mdi-arrow-up-bold-box-outline
                                </v-icon>
                            </v-card-title>
                            <v-card-text>
                                <p>Total de mêses não pago</p>
                                <h4 align="center" id="atualizacaoPendente">{{ mesesNPago }}</h4>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-0" color="white">
                        <inertia-link
                         
                            class="color-text-link remover-link"
                        >
                        <v-card-title
                                id="atualizacao"
                                class="center"
                                align="center"
                            >
                                <v-icon large color="#FFECBD">
                                    mdi-arrow-up-bold-box-outline
                                </v-icon>
                            </v-card-title>
                            <v-card-text>

                                <p>Total de Apartamentos</p>
                                <h4 align="center" id="atualizacaoEmAvaliacacao">{{ apartamentos }}</h4>
                            </v-card-text>
                        </inertia-link>
                    </v-card>
                </v-col>
                <v-col xs="12" sm="6" md="3" dense>
                    <v-card elevation="1" class="px-1" color="white">
                        <inertia-link
                          
                            class="color-text-link remover-link"
                        >
                        <v-card-title
                                id="atualizacao"
                                class="center"
                                align="center"
                            >
                                <v-icon large color="#4caf50">
                                    mdi-arrow-up-bold-box-outline
                                </v-icon>
                            </v-card-title>
                            <v-card-text>

                                <p>Total de condomino </p>
                                <h4 align="center" id="atualizacaoFinalizado">{{ condominos }}</h4>
                            </v-card-text>
                        
                        </inertia-link>
                    </v-card>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="6" md="8">
                    <v-card elevation="0">
                        <v-card>
                            <template elevation="4">
                                <v-card>
                                    <v-tabs
                                        color="teal"
                                        class="font-weight-bolder"
                                    >
                                        <v-tab> Apartamentos Pagos </v-tab>
                                        <v-tab>
                                            <v-icon left> </v-icon>
                                            Apartamentos Não Pagos
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

                <v-col cols="6" md="4">
                    <v-card elevation="0">
                        <v-card-text
                            class="text-subtitle-1 font-weight-bold text-black"
                            >Sindicos com Cotas mais pagas</v-card-text
                        >
                        <v-card light flat>
                            <v-container>
                                <v-layout align-center>
                                    <strong
                                        class="display-1 font-weight-regular mr-4"
                                        >{{ currentDate("") }}</strong
                                    >
                                    <v-layout column justify-end>
                                        <div class="title font-weight-light">
                                            {{ currentDate("dia") }}
                                        </div>
                                        <div
                                            class="text-uppercase font-weight-light"
                                        >
                                            {{ currentDate("mes e ano") }}
                                        </div>
                                    </v-layout>
                                </v-layout>
                            </v-container>
                        </v-card>
                        <v-card-text class="py-0">
                            <v-timeline align-top dense>
                                <v-timeline-item
                                    color="#076461"
                                    small
                                    v-for="condomino in condominosSemDividas"
                                    :key="condomino.id"
                                >
                                    <v-row class="pt-1">
                                        <v-col cols="12">
                                            <strong
                                                >{{
                                                    condomino.nome_pessoa
                                                }}</strong
                                            >
                                        </v-col>
                                        <v-col>
                                           
                                            <!-- <div class="caption">Blah blah</div> -->
                                            <!-- <v-avatar>
                                                <v-img
                                                    src="https://avataaars.io/?avatarStyle=Circle&topType=LongHairMiaWallace&accessoriesType=Sunglasses&hairColor=BlondeGolden&facialHairType=Blank&clotheType=BlazerSweater&eyeType=Surprised&eyebrowType=RaisedExcited&mouthType=Smile&skinColor=Pale"
                                                ></v-img>
                                            </v-avatar>
                                            <span class="caption grey--text"
                                                >Steven Morrison</span
                                            > -->
                                        </v-col>
                                    </v-row>
                                </v-timeline-item>
                            </v-timeline>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "../Shared/AppLayout";
// import Chart from "chart.js/auto";
// import { ColumnChart } from 'vuejs-highcharts'

export default {
    // name: 'BasicChartSample',
    props: [
        'condominosSemDividas',
        'condominos',
        'mesesNPago',
        'valorPago',
        'apartamentos',

    ],
    components: {
        AppLayout,
        // ColumnChart
    },
    data() {
        return {
        };
    },

    created() {
        this.renderizarDadosDashboard();
        // alert(1);
    },

    methods: {
        renderizarDadosDashboard() {
            // alert(JSON.stringify('ola'));
            axios
                .get("/dashboard")
                .then((response) => {
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },

        currentDate(data) {
            const current = new Date();
            const dayOfWeek = current.toLocaleString("default", {
                weekday: "long",
            });
            const month = current.toLocaleString("default", { month: "long" });
            const capitalizedDayOfWeek =
                dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1);
            const day = current.getDate();
            const year = current.getFullYear();
            const date = `${month} ${year}`;
            /* const date = `${current.getDate()}/${month}/${current.getFullYear()}`; */

            if (data == "mes e ano") {
                return date;
            }
            if (data == "dia") {
                return capitalizedDayOfWeek;
            } else {
                return day;
            }
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
    color: #EA5F46 !important;
}

#atualizacaoAndamento {
    color: #0E85A3;
}

#atualizacaoEmAvaliacacao {
    color: #FFECBD;
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

.v-card.v-sheet.theme--light .card_b {
    background-color: #ffffff !important;
    border-left: 5px solid #034078 !important;
}

.v-card.v-sheet.theme--light .card_g {
    background-color: #ffffff !important;
    border-left: 5px solid #1e4b32 !important;
}

.v-card.v-sheet.theme--light .card_gr {
    background-color: #ffffff !important;
    border-left: 5px solid rgba(255, 166, 0, 0.884) !important;
}

.v-card.v-sheet.theme--light .card_or {
    background-color: #ffffff !important;
    border-left: 5px solid #d62828 !important;
}

/* .user-group-card {
    max-width: 300px;
    margin: 10px;
}

.total-users,
.group-name,
.avatar-list,
.arrow {
    margin: 10px;
}

.total {
    font-size: 24px;
    font-weight: bold;
}

.name {
    font-size: 16px;
}

.avatar-list v-avatar {
    margin-right: 5px;
}

.arrow v-icon {
    cursor: pointer;
} */

.avatars {
    display: flex;
    list-style-type: none;
    margin: auto;
    padding: 0px;
    flex-direction: row;
}
.avatars__item {
    background-color: #596376;
    border-radius: 100%;
    color: #fff;
    display: block;
    font-family: sans-serif;
    font-size: 12px;
    font-weight: 100;
    height: 30px;
    width: 30px;
    line-height: 30px;
    text-align: right;
    transition: margin 0.1s ease-in-out;
    overflow: hidden;
    margin-left: -10px;
}
.avatars__item:first-child {
    z-index: 5;
}
.avatars__item:nth-child(2) {
    z-index: 4;
}
.avatars__item:nth-child(3) {
    z-index: 3;
}
.avatars__item:nth-child(4) {
    z-index: 2;
}
.avatars__item:nth-child(5) {
    z-index: 1;
}
.avatars__item:last-child {
    z-index: 0;
}
.avatars__item img {
    width: 100%;
}
.avatars:hover .avatars__item {
    margin-right: 10px;
}
</style>
