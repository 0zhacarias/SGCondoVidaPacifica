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
                                                <td>{{ (item.preco).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' }) }}</td>
                                                <td>
                                                  
                                                    <v-text-field v-model="item.quantidade" type="number" min="0"
                                                        max="12" dense @keyup.enter="TotalGeral(servicos_selecionado)"
                                                        @input="TotalGeral(servicos_selecionado)"
                                                         :rules="quantiaddeRules" :error-messages="erros.designacao" required
                                                        >
                                                    </v-text-field>
                                             
                                                </td>
                                                <td v-if="item.quantidade">{{ (item.total_g=item.preco * item.quantidade).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
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
        "index",
       
    ],
    components: {
        AppLayout,
        TabApartamento,
    },

    data() {
        return {
         
            
            factura: {
            },
           
            servicos_selecionado: [],
            todos_servicos: [],
                      erros: [],
            // Front nao aceitar campo em branco.

          
            //Validar Responsavel
            quantiaddeRules: [
                (v) => !!v || "Data Obrigatório",
                (v) =>
                    v <= 12 || "A quantidade não pode ser superior que 12",
            ],
            total_quantidade: 0,
            total_preco: 0,
            total_geral: 0,
            servicos_map: [],
        };
    },
    methods: {

         Servicos(item) {
            let dados = this.servicos_map.find((elem) => elem.id == item)
            //let dadosRemov=
            this.servicos_map=this.servicos_map.filter(elem => elem.id !=item)
           // alert(JSON.stringify(this.servicos_map))
            this.todos_servicos = this.servicos_selecionado.push(dados)


        },
        TotalGeral(total) {
            this.total_quantidade = total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.quantidade), 0)
            this.total_preco = (total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.preco), 0)).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
            this.total_geral =  (total.reduce((primeiro, ultimo) => primeiro + parseInt(ultimo.total_g), 0)).toLocaleString('pt-AO', { style: 'currency', currency: 'AOA' })
            
        },
        EmitirFatura() {
            let qmenor12=this.servicos_selecionado.some((item)=>item.quantidade>12)
            if(qmenor12==false){
            
                    alert(JSON.stringify(qmenor12))
            axios.post('/financas/emitir-factura', {
                servicos: this.servicos_selecionado,
            }).then((response) => {
                
                if(response.data.factura_id){
                    window.open('/relatorios/factura/' + response.data.factura_id)
                this.servicos_selecionado = []
                this.pagamento = []
                this.total_quantidade = 0
                this.total_preco = 0
                this.total_geral = 0
                }else{
                    Vue.toasted.global.defaultError({
                                msg: "" + response.data.error,
                            });
                }
               
            }).catch((error) => {
                   // toastr.warning('Houve uma falha ao carregar os dados!...');
                });
            }}
        },
        tarefaPendente(item) {
            window.open("certificado-entrada/" + btoa(btoa(btoa(item.id))));
        },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    mounted() {

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
