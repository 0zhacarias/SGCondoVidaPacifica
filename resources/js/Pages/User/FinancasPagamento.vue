<template>
    <app-layout>
        <div class="dashboard">
            <v-card elevation="0">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">Pagamento</h3>
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
                        <v-card flat>
                            <v-card-text>
                                <v-row class="m-0 p-0">
                                    <v-col class="p-1" v-for="servico in item.servicos" :key="servico"
                                        :md="item.servicos.length > 4 ? 2 : 3">
                                        <v-card :color="servico.created_by == user.responsavel.id ? 'green' : 'red'">
                                            <v-card-text>
                                                <h4>Serviços :{{ servico.created_by == user.responsavel.id ? "Pago" :
                                                    "Não pago" }}</h4>
                                                {{
                                                    servico.descricao }}
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row> </v-card-text>
                        </v-card>
                    </v-tab-item>

                </v-tabs-items>
            </v-card>

        </div>
        <v-card class="elevation-0 mb-12 mt-4 ">
            <template>
  <v-data-table
    :headers="headersPagamento"
    :items="dividas"
    item-value="condomino_id"
    class="elevation-1"
    dense
  >
    <template v-slot:body="{ items }">
      <tbody>
        <tr v-for="(item, index) in items" :key="item.condomino_id">
          <!-- ID e Nome com rowspan -->
          <td :rowspan="1">
            <strong>{{ item.condomino_id }}</strong>
          </td>
          <td :rowspan="1">
            <strong>{{ item.nome }}</strong>
          </td>

          <!-- Primeiro Serviço -->
          
          <td>{{ item.servicos.length > 0 ? item.servicos[0].servico : "—" }}</td>
          <td v-for="mes in meses" :key="mes">
            {{ item.servicos.length > 0 ? item.servicos[0][mes] || "—" : "—" }}
          </td>
        </tr>
        <!-- Serviços adicionais -->
      <!--   <tr
          v-for="(servico, index) in items.servicos"
          :key="`${item.condomino_id}-servico-${index}`"
          v-if="index > 0"
        >
          <td>{{ servico.servico }}</td>
          <td v-for="mes in meses" :key="mes">
            {{ servico[mes] || "—" }}
          </td>
        </tr> -->
      </tbody>
    </template>
  </v-data-table>
</template>
        </v-card>
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
            meses: [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            headersPagamento: [
                { text: 'ID do Cliente', value: 'condomino_id' },
                { text: 'Nome do Cliente', value: 'nome' },
                { text: 'Serviço', value: 'servico' },
                ...[
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ].map(mes => ({ text: mes, value: mes })),
                
            ],
            // meses: [], // Será preenchido dinamicamente
            dividas: [],

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
        fetchDividas() {
            axios.get('/financas/dividas')
                .then(response => {
                    // Define a ordem fixa dos meses para garantir a consistência
                    const ordemMeses = [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril',
                        'Maio', 'Junho', 'Julho', 'Agosto',
                        'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ];

                    // Processa os dados
                    const arryachatado = response.data.flat()

                    this.dividas = arryachatado.map(function (item) {
                        return {
                            condomino_id: item.condomino_id,
                            nome: item.nome,
                            servicos: item.servicos,
                            /*  condomino_id,nome,servicos */
                        }
                    })
                    //alert(JSON.stringify(dividas))
                    /*   this.dividas = response.data.map(cliente => 
                        cliente.servicos.map(servico => ({
                          condomino_id: cliente.condomino_id,
                          nome: cliente.nome,
                          servico: servico.servico
                        }))
                      );
                 */
                    // Atualiza os headers mantendo a ordem dos meses
                    /* this.headersPagamento = [
                      { text: 'ID', value: 'condomino_id' },
                      { text: 'Nome', value: 'nome' },
                      { text: 'Serviço', value: 'servico' },
                      ...ordemMeses.map(mes => ({ 
                        text: mes, 
                        value: mes,
                        align: 'center',
                        sortable: false 
                      }))
                    ]; */
                })
                .catch(error => {
                    console.error('Erro ao buscar dívidas:', error);
                    this.dividas = []; // Limpa dados em caso de erro
                });
        },
       /*  getServiceStatus(item, mes) {
            if (!item.servicos || !item.servicos.length) return '';
            return item.servicos
                .map(s => s[mes])
                .filter(status => status)
                .join(' | ');
        } */
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
        this.fetchDividas();
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
