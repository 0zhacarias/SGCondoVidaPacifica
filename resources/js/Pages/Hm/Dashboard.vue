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
                                <v-icon large color="#0E85A3">
                                    mdi-arrow-up-bold-box-outline
                                </v-icon>
                            </v-card-title>
                            <v-card-text>
                                <!--         <h1 align="center">
                                    {{ contarTarefasPendente }}
                                </h1> -->

                                <p>Total de mês</p>
                                <h4 id="atualizacaoAndamento">{{ contarTarefasPendente }}</h4>
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
                                <!--         <h1 align="center">
                                    {{ contarTarefasPendente }}
                                </h1> -->

                                <p>Total de faltas diário</p>
                                <h4 id="atualizacaoPendente">{{ contarTarefasPendente }}</h4>
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
                                <!--         <h1 align="center">
                                    {{ contarTarefasPendente }}
                                </h1> -->

                                <p>Total de Condominios</p>
                                <h4 id="atualizacaoEmAvaliacacao">{{ contarTarefasPendente }}</h4>
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
                                <!--         <h1 align="center">
                                    {{ contarTarefasPendente }}
                                </h1> -->

                                <p>Total de Sindicos </p>
                                <h4 id="atualizacaoFinalizado">{{ contarTarefasPendente }}</h4>
                            </v-card-text>
                        
                        </inertia-link>
                    </v-card>
                </v-col>
            </v-row>

            <!--      <v-row class="pt-8">
                <v-col cols="3" style="display: inline-grid">
                    <v-card class="mx-auto pt-7">
                        <v-card-text class="pt-0">
                            <v-card-text
                                class="text-h6 font-weight-bold text-black"
                            >
                                Tarefas
                            </v-card-text>
                            <v-row>
                                <v-col cols="6">
                                    <inertia-link
                                        :href="
                                            '/tarefas/listar-tarefa/' +
                                            tarefa(1)
                                        "
                                        class="color-text-link remover-link"
                                    >
                                        <v-hover v-slot="{ hover }">
                                            <v-card
                                                class=""
                                                elevation="0"
                                                max-height="70"
                                                :elevation="hover ? 6 : 0"
                                            >
                                                <v-list-item>
                                                    <v-list-item-content
                                                        class="py-0"
                                                    >
                                                        <div
                                                            class="text-overline"
                                                        >
                                                            Pendentes
                                                        </div>
                                                        <v-list-item-title
                                                            class="text-h5 card_or mb-2"
                                                        >
                                                            <span
                                                                class="pl-2 font-weight-bold"
                                                                >{{
                                                                    tpendente
                                                                }}</span
                                                            >
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-card>
                                        </v-hover>
                                    </inertia-link>
                                </v-col>
                                <v-col cols="6">
                                    <inertia-link
                                        :href="
                                            '/tarefas/listar-tarefa/' +
                                            tarefa(2)
                                        "
                                        class="color-text-link remover-link"
                                    >
                                        <v-hover v-slot="{ hover }">
                                            <v-card
                                                class=""
                                                elevation="0"
                                                max-height="70"
                                                :elevation="hover ? 6 : 0"
                                            >
                                                <v-list-item>
                                                    <v-list-item-content
                                                        class="py-0"
                                                    >
                                                        <div
                                                            class="text-overline"
                                                        >
                                                            Em andamento
                                                        </div>
                                                        <v-list-item-title
                                                            class="text-h5 card_b mb-2"
                                                        >
                                                            <span
                                                                class="pl-2 font-weight-bold"
                                                                >{{
                                                                    tandamento
                                                                }}</span
                                                            >
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-card>
                                        </v-hover>
                                    </inertia-link>
                                </v-col>
                                <v-col cols="6">
                                    <inertia-link
                                        :href="
                                            '/tarefas/listar-tarefa/' +
                                            tarefa(3)
                                        "
                                        class="color-text-link remover-link"
                                    >
                                        <v-hover v-slot="{ hover }">
                                            <v-card
                                                class=""
                                                elevation="0"
                                                max-height="70"
                                                :elevation="hover ? 6 : 0"
                                            >
                                                <v-list-item>
                                                    <v-list-item-content
                                                        class="py-0"
                                                    >
                                                        <div
                                                            class="text-overline"
                                                        >
                                                            Em avaliação
                                                        </div>
                                                        <v-list-item-title
                                                            class="text-h5 card_gr mb-2"
                                                        >
                                                            <span
                                                                class="pl-2 font-weight-bold"
                                                                >{{
                                                                    tavaliacao
                                                                }}</span
                                                            >
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-card>
                                        </v-hover>
                                    </inertia-link>
                                </v-col>
                                <v-col cols="6">
                                    <inertia-link
                                        :href="
                                            '/tarefas/listar-tarefa/' +
                                            tarefa(4)
                                        "
                                        class="color-text-link remover-link"
                                    >
                                        <v-hover v-slot="{ hover }">
                                            <v-card
                                                class=""
                                                elevation="0"
                                                max-height="70"
                                                :elevation="hover ? 6 : 0"
                                            >
                                                <v-list-item>
                                                    <v-list-item-content
                                                        class="py-0"
                                                    >
                                                        <div
                                                            class="text-overline"
                                                        >
                                                            Finalizadas
                                                        </div>
                                                        <v-list-item-title
                                                            class="text-h5 card_g mb-2"
                                                        >
                                                            <span
                                                                class="pl-2 font-weight-bold"
                                                                >{{
                                                                    tfinalizado
                                                                }}</span
                                                            >
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-card></v-hover
                                        >
                                    </inertia-link>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="5" style="display: inline-grid"
                    ><v-card>
          
                        <v-card
                            style="width: max-content"
                            elevation="0"
                            class="mx-auto"
                        >
                            <pie-chart
                                :donut="true"
                                :data="[
                                    ['Pendentes', this.ppendente],
                                    ['Em andamento', this.pandamento],
                                    ['Em avaliação', pavaliacao],
                                    ['Finalizado', pfinalizado],
                                    ['Em atraso', patrasado],
                                ]"
                                :colors="[
                                    '#3498db',
                                    '#e74c3c',
                                    '#2998a9',
                                    '#0e5ca9',
                                ]"
                                legend="right"
                                :title="'Percentagem de Projectos'"
                                :library="{
                                    plotOptions: {
                                        pie: {
                                            allowPointSelect: true,
                                            cursor: 'pointer',
                                            dataLabels: {
                                                enabled: true,
                                                format: '<b>{point.name}</b>: {point.percentage:.1f}% ',
                                            },
                                            borderWidth: 9,
                                            showInLegend: true,
                                        },
                                    },
                                }"
                            ></pie-chart
                        ></v-card> </v-card
                ></v-col>
                <v-col cols="4" style="display: inline-grid" class="mx-auto">
                    <v-card>
                        <v-card
                            style="width: max-content"
                            elevation="0"
                            class="mx-auto pa-7"
                        >
                            <v-card-text
                                class="text-h6 font-weight-bold text-black"
                            >
                                Nº de projectos por departamento
                            </v-card-text>

                            <v-card max-width="400" elevation="0" class="ma-4">
                                <v-row class="ma-2">
                                    <v-col>
                                        <v-card-text
                                            class="text-subtitle-1 font-weight-bold text-black px-0"
                                        >
                                            {{ php }}
                                        </v-card-text>
                                        <v-row>
                                            <v-col cols="6"
                                                ><v-icon color="#4f5b93"
                                                    >mdi
                                                    mdi-language-php</v-icon
                                                ></v-col
                                            >
                                            <v-col cols="7">PHP</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col>
                                        <v-card-text
                                            class="text-subtitle-1 font-weight-bold text-black px-0"
                                        >
                                            {{ java }}
                                        </v-card-text>
                                        <v-row>
                                            <v-col cols="6"
                                                ><v-icon color="#e76f00"
                                                    >mdi
                                                    mdi-language-java</v-icon
                                                ></v-col
                                            >
                                            <v-col cols="7">JSF</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col>
                                        <v-card-text
                                            class="text-subtitle-1 font-weight-bold text-black px-0"
                                        >
                                            {{ InfraEstrutura }}
                                        </v-card-text>
                                        <v-row>
                                            <v-col cols="4"
                                                ><v-icon color="black"
                                                    >mdi
                                                    mdi-access-point-network</v-icon
                                                ></v-col
                                            >
                                            <v-col>Infraestrutura</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col>
                                        <v-card-text
                                            class="text-subtitle-1 font-weight-bold text-black px-0"
                                        >
                                            {{ Mobile }}
                                        </v-card-text>
                                        <v-row>
                                            <v-col
                                                ><v-icon color="black"
                                                    >mdi mdi-cellphone</v-icon
                                                ></v-col
                                            >
                                            <v-col>Mobile</v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-card>
                    </v-card>
                </v-col>
            </v-row> -->
            <!-- Por concluir depois -->
            <!--     <v-row class="pt-2">
                <p>Projectos actuais e os seus estados</p>
                <v-col><v-card>Projectos com v-for</v-card></v-col>
            </v-row> -->

            <!--  <v-row class="pt-2" style="line-height: normal">
                <v-card-text class="text-subtitle-1 font-weight-bold text-black"
                    >Membros</v-card-text
                >
                <v-col v-for="(group, index) in arrayAgrupamento" :key="index"
                    ><v-card class="pa-5"
                        >Total de usuários
                        <v-row align="center" justify="end">
                            <v-col cols="12">
                                <v-row>
                                    <v-col cols="6">
                                        <div class="text-h5">
                                            {{ group.totalUsers }}
                                        </div>
                                    </v-col>
                                    <v-col cols="6" class="text-right">
                                        <v-icon @click="goBack"
                                            >mdi-arrow-right</v-icon
                                        >
                                    </v-col>
                                </v-row>
                                 Lista de avatares
                                <v-row align="center" justify="start">
                                    <v-col cols="5">
                                        <div class="name">
                                            {{ group.groupName }}
                                        </div>
                                    </v-col>
                                    <v-col cols="7" class="text-right">
                                        <div
                                            class="avatars"
                                            style="display: inline-flex"
                                        >
                                            <v-avatar
                                                size="30"
                                                href="#"
                                                class="avatars__item"
                                                v-for="(
                                                    user, index
                                                ) in group.recentUsers"
                                                :key="index"
                                                ><v-img
                                                    class="avatar"
                                                    src="https://cdn.vuetifyjs.com/images/john.jpg"
                                                    alt=""
                                                ></v-img
                                            ></v-avatar>
                                            <inertia-link
                                                href="#"
                                                class="avatars__item"
                                            >
                                                <v-avatar
                                                    size="30"
                                                    v-if="
                                                        group.utilizadoresRestantes >
                                                        0
                                                    "
                                                >
                                                    +{{
                                                        group.utilizadoresRestantes
                                                    }}
                                                </v-avatar></inertia-link
                                            >
                                        </div>
                                    </v-col>
                                </v-row></v-col
                            >
                        </v-row>
                    </v-card></v-col
                >
            </v-row> -->
            <v-row>
                <v-col cols="10">
                    <!-- <template>
                        <ColumnChart :xAxis="chart.xAxis" :series="chart.series" />
                    </template> -->
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="6" md="8">
                    <!-- <v-card elevation="3">
                        <v-subheader class="font-weight-regular corprincipal white--text mt-4">
                        <span style="font-weight: bolder">
                            <h2>NÍVEL DE DESEMPEIO SEMANAL </h2>
                        </span>
                        </v-subheader>
                    </v-card> -->

                    <v-card elevation="0">
              <!--           <v-card-text
                            class="text-subtitle-1 font-weight-bold text-black"
                            >Gráficos dos Sindicos!</v-card-text> -->

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
            arrayAgrupamento: [
                {
                    totalUsers: 50,
                    groupName: "Programadores",
                    recentUsers: [
                        { img: "link-para-avatar1" },
                        { img: "link-para-avatar2" },
                        { img: "link-para-avatar3" },
                    ],
                    utilizadoresRestantes: 47,
                },
                {
                    totalUsers: 20,
                    groupName: "Analistas",
                    recentUsers: [
                        { img: "link-para-avatar1" },
                        { img: "link-para-avatar2" },
                        { img: "link-para-avatar3" },
                    ],
                    utilizadoresRestantes: 17,
                },
                {
                    totalUsers: 10,
                    groupName: "Gestores de projectos",
                    recentUsers: [
                        { img: "link-para-avatar1" },
                        { img: "link-para-avatar2" },
                        { img: "link-para-avatar3" },
                    ],
                    utilizadoresRestantes: 10,
                },
                {
                    totalUsers: 5,
                    groupName: "DBA",
                    recentUsers: [
                        { img: "link-para-avatar1" },
                        { img: "link-para-avatar2" },
                        { img: "link-para-avatar3" },
                    ],
                    utilizadoresRestantes: 2,
                },
            ],

            tarefas: {},
            projetos: 0,
            php: 0,
            java: 0,
            InfraEstrutura: 0,
            Mobile: 0,

            ppendente: 0,
            pandamento: 0,
            pavaliacao: 0,
            pfinalizado: 0,
            patrasado: 0,
            ppoducao: 0,

            tpendente: 0,
            tandamento: 0,
            tavaliacao: 0,
            tfinalizado: 0,
            tatrasado: 0,

            gestores: 0,
            tandamento: 0,
            tavaliacao: 0,
            tfinalizado: 0,
            tatrasado: 0,

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
        this.Tipo_tecnologias();
        this.percentagem_estados_projectos();
        // alert(1);
    },

    methods: {
        goBack() {
            // Implementar a lógica para voltar ou realizar outra ação desejada
            console.log("Voltar");
        },
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

        // metodo que cria o link dos card para dar acesso as tarefas e criptografar o caminho
        tarefa(item) {
            // window.open("/tarefas/listar-tarefa/" + btoa(btoa(btoa(item))));
            return btoa(btoa(btoa(item)));
        },
        Tipo_tecnologias() {
            axios
                .get("/projectos-departamento-dasboard")
                .then((response) => {
                    this.php = response.data.php;
                    this.java = response.data.java;
                    this.Mobile = response.data.Mobile;
                    this.InfraEstrutura = response.data.InfraEstrutura;
                    this.Mobile = response.data.Mobile;
                    this.opnSource = response.data.opnSource;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        percentagem_estados_projectos() {
            axios
                .get("/percentagem-estados-projectos")
                .then((response) => {
                    this.ppendente = response.data.quantidade_projecto_pendente;
                    // alert(JSON.stringify(response.data.quantidade_projecto_andamento));
                    this.pandamento =
                        response.data.quantidade_projecto_andamento;
                    this.pavaliacao =
                        response.data.quantidade_projecto_avaliacao;
                    this.pfinalizado =
                        response.data.quantidade_projecto_finalizado;
                    this.patrasado = response.data.quantidade_projecto_atrasado;
                    this.ppoducao = response.data.quantidade_projecto_producao;

                    this.tpendente = response.data.quantidade_tarefa_pendente;
                    this.tandamento = response.data.quantidade_tarefa_andamento;
                    this.tavaliacao = response.data.quantidade_tarefa_avaliacao;
                    this.tfinalizado =
                        response.data.quantidade_tarefa_finalizado;
                    this.tatrasado = response.data.quantidade_tarefa_atrasado;

                    this.gestores = response.data.gestores;
                    this.tandamento = response.data.quantidade_tarefa_andamento;
                    this.tavaliacao = response.data.quantidade_tarefa_avaliacao;
                    this.tfinalizado =
                        response.data.quantidade_tarefa_finalizado;
                    this.tatrasado = response.data.quantidade_tarefa_atrasado;
                })

                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
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
