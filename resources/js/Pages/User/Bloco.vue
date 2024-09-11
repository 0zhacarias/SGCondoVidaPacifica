<template>
    <app-layout>
            <v-card elevation="0">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">
                            Blocos ({{ this.blocos.length }})
                        </h3>
                    </v-col>

                    <v-col class="text-right">
                        <v-btn color="corprincipal" title="Adicionar novo projecto" class="white--text font-weight-bold"
                            @click="carregarDialog()">Adicionar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>

            <v-card class="my-5 elevation-0">
                <div class="text-h5 font-weight-bold" style="color: #0a6e84">
                    Filtros <span class="mdi mdi-filter-outline"></span>
                </div>
                <v-card-title>
                    <v-row >
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Bloco</label>
                            <!-- <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Pesquisar projectos"
                dense

              >
              </v-text-field> -->
                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query.projecto_id"
                                :items="filtroProjectos" item-value="id" item-text="nome_proj" type="text" outlined
                                clearable dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Sindicos</label>
                            <v-autocomplete prepend-icon="" @change="filtrarEstado()" v-model="query.responsavel_id"
                                :items="gestores" item-value="id" item-text="nome_pessoa" type="text" outlined clearable
                                dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Nº apartamento</label>
                            <v-text-field @change="filtrarEstado()" clearable v-model="query.estado_bloco_id"
                                :items="estadoblocos" item-text="designacao" item-value="id" prepend-icon="" outlined
                                dense>
                            </v-text-field>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Tipologia</label>
                            <v-autocomplete @change="filtrarEstado()" clearable v-model="query.estado_bloco_id"
                                :items="estadoblocos" item-text="designacao" item-value="id" prepend-icon=""
                                label="Tipologia" outlined dense>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3" class="text-right">
                            <label for=""></label>
                            <v-text-field @input="filtrarData()" label="Data de Criação" v-model="query.data_inicial"
                                type="date" :rules="dataInicialfiltroRules" :error-messages="erros.dat_inicio_real"
                                outlined dense>
                            </v-text-field>
                            <v-btn  outlined rounded title="Adicionar novo projecto" class="font-weight-bold"
                                @click="carregarDialog()">Pesquisar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-title>
            </v-card>
            <v-card class="elevation-0">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="12"> </v-col>
                    </v-row>

                    <v-data-iterator :items="blocos" :items-per-page.sync="itemsPerPage" :page.sync="page"
                        :search="search" hide-default-footer>
                        <template v-slot:default="props">
                            <v-row align="center" justify="center" dense>
                                <v-col v-for="item in props.items" :key="item.nome_proj"
                                    @click="tarefasProjecto(item.id)" cols="12" sm="12" md="6"
                                    :lg="blocos.length <= 3 ? 6 : 3">
                                    <v-card class="mx-auto" color="#0E85A3" dark max-width="400">
                                        <v-card-title style="background-color: #19a1be">
                                            <v-icon large center>
                                                mdi mdi-home-city
                                            </v-icon>
                                            <!--       <span class="text-h6 font-weight-light">Twitter</span> -->
                                        </v-card-title>

                                        <div class="text-h6 ml-1">
                                            <div>
                                                Bloco
                                                <span class="font-weight-bold">{{ item.descricao_bloco }}</span>
                                            </div>
                                            <div>
                                                Sindico
                                                <span class="font-weight-bold">{{ item.sindico.nome_pessoa }}
                                                    {{ item.sindico.sobre_nome_pessoa }}</span>
                                            </div>
                                            <div>
                                                Nº de Apartamentos
                                                <span class="font-weight-bold">{{ item.numero_apartamento }}</span>
                                            </div>
                                        </div>

                                        <v-card-actions class="center" align="center">
                                            <v-list-item class="grow">
                                                <!--                                                       <v-list-item-avatar color="grey darken-3">
          <v-img
            class="elevation-6"
            alt=""
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-item-avatar> 
         align="right"
                                                    justify="end"-->

                                                <v-row>
                                                    <v-btn icon color="green" v-on:click.stop="
                                                        verDetalhe(item)
                                                        " title="Visualizar Projecto">
                                                        <v-icon small>visibility</v-icon>
                                                    </v-btn>
                                                    <v-btn icon color="orange" v-on:click.stop="editItem(item)"
                                                        title="Editar ">
                                                        <v-icon small>edit</v-icon>
                                                    </v-btn>
                                                    <v-btn icon v-on:click.stop="
                                                        deleteItem(item)
                                                        " color="red" title="eliminar" :disabled="item.estado_bloco_id == 2
                                                    ">
                                                        <v-icon small>mdi-delete</v-icon>
                                                    </v-btn>
                                                    <v-btn :disabled="!item.arquivos" icon v-on:click.stop="
                                                        redirect(item.arquivos)
                                                        " color="blue" title="Visualizar a documentação do bloco">
                                                        <v-icon small>attach_file</v-icon>
                                                    </v-btn>
                                                    <v-btn justify="end"  align="right" style="
                                                            background-color: #19a1be;
                                                        " rounded dark icon>
                                                        Ver
                                                        
                                                    </v-btn>
                                                    <v-menu transition="slide-x-transition" bottom right>
                                                        <template v-slot:activator="{
                                                            on,
                                                            attrs,
                                                        }">
                                                            <v-btn color="black" dark icon v-bind="attrs" v-on="on"
                                                                title="Adicionar responsaveis">
                                                                <v-icon>mdi-dots-vertical</v-icon>
                                                            </v-btn>
                                                        </template>

                                                        <v-list>
                                                            <v-list-item>
                                                                <v-list-item-title>
                                                                    <a icon color="orange" class="text-decoration-none"
                                                                        @click="
                                                                            adicionarResponsavel(
                                                                                item
                                                                            )
                                                                            " title="Adicionar Responsavel">
                                                                        Adicionar
                                                                        Responsavel
                                                                    </a>
                                                                </v-list-item-title>
                                                            </v-list-item>
                                                        </v-list>
                                                    </v-menu>

                                                </v-row>
                                            </v-list-item>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:footer>
                            <v-row class="mt-2" align="center" justify="center">
                                <v-col sm="6" md="7">
                                    <span class="grey--text">Mostrar</span>
                                    <v-menu offset-y>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn outlined class="ml-2" v-bind="attrs" v-on="on">
                                                {{ itemsPerPage }}
                                                <v-icon>mdi-chevron-down</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item v-for="(
                                                    number, index
                                                ) in itemsPerPageArray" :key="index" @click="
                                                    updateItemsPerPage(number)
                                                    ">
                                                <v-list-item-title>{{
                                                    number
                                                    }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-col>

                                <v-col sm="6" md="4" class="" align="right">
                                    <span class="mr-4 grey--text">
                                        Páginas {{ page }} de
                                        {{ numberOfPages }}
                                    </span>
                                    <v-btn small fab text color="corprincipal" class="mr-1" @click="formerPage">
                                        <v-icon>mdi-chevron-left</v-icon>
                                    </v-btn>
                                    <v-btn small fab text color="corprincipal" class="ml-1" @click="nextPage">
                                        <v-icon>mdi-chevron-right</v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </template>
                    </v-data-iterator>
                </v-card-text>
            </v-card>

            <v-dialog v-if="dialog" v-model="dialog" width="800" persistent>
                <v-card>
                    <v-toolbar class="text-uppercase font-weight-bold" elevation="0">
                        <v-toolbar-title>
                            {{
                                editedIndex == -1
                                    ? "Adicionar Bloco"
                                    : "Atualizar Bloco"
                            }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-icon color="red" @click="cancelarDialog">mdi-close</v-icon>
                    </v-toolbar>
                    <v-card-text>
                        <v-stepper flat v-model="e1">
                            <v-stepper-items flat>
                                <v-stepper-content step="1" class="pa-1">
                                    <v-card class="mb-12" flat>
                                        <v-form ref="formbloco" lazy-validation>
                                            <v-card-text>
                                                <div>
                                                    <v-row>

                                                        <v-col cols="5">

                                                            <v-text-field outlined dense v-model="bloco.designacao
                                                                " label="Designação do bloco" class="pb-4">
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col cols="3">

                                                            <v-text-field outlined dense v-model="bloco.numero_apartamento
                                                                " label="número de apartamentos" hide-details="auto"
                                                                class="pb-4">
                                                            </v-text-field>
                                                        </v-col>

                                                        <v-col xs="12" sm="12" md="4">
                                                            <v-autocomplete outlined dense v-model="bloco.sindico_id
                                                                " :items="gestores
                                                        " item-value="id" item-text="nome_pessoa" :rules="responsavelRules
                                                        " :error-messages="erros.responsavel_id
                                                        " :menu-props="{
                                                        bottom: true,
                                                        offsetY: true,
                                                    }" label="Sindico!" />
                                                        </v-col>
                                                    </v-row>

                                                    <v-textarea rows="3" v-model="bloco.descricao_bloco
                                                        " outlined autocomplete="Descrição do bloco"
                                                        label="Descrição do bloco" :rules="descricaoBlocoRules
                                                            " :error-messages="erros.descricao_bloco
                                                                    "></v-textarea>
                                                </div>
                                            </v-card-text>
                                            <v-flex top class="text-right"></v-flex>
                                            <v-spacer />
                                            <div class="text-right">
                                                <v-btn color="teal" dark @click="save()" v-if="
                                                    editedIndex ==
                                                    -1
                                                ">Guardar</v-btn>
                                                <v-btn color="teal" dark @click="save()" v-if="
                                                    editedIndex > -1
                                                ">Actualizar</v-btn>
                                            </div>

                                        </v-form>
                                    </v-card>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>

                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDetalhebloco" v-model="dialogDetalhebloco" fullscreen hide-overlay
                transition="dialog-bottom-transition">
                <v-card class="grey lighten-5">
                    <v-toolbar class="text-h6 text-dark dark">
                        <v-spacer></v-spacer>
                        Detalhes do Bloco
                        <v-btn icon dark @click="dialogoDetalhebloco()">
                            <v-icon color="black">mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-actions>
                        <v-btn color="black" text :href="'/projectos/arquivo-pdf-projecto/' + bloco.id
                            " target="__blank" class="remover-link">
                            Exportar Dados:
                            <v-icon title="Exportar em pdf" color="black">
                                mdi-file-export</v-icon>
                        </v-btn>
                    </v-card-actions>
                    <v-container>
                        <v-card>
                            <v-list three-line subheader>
                                <v-row justify="center">
                                    <v-col cols="12" sm="10" class="ma-3 pa-0">
                                        <v-sheet class="ma-0 pa-0">
                                            <v-subheader class="font-weight-bold ml-0 mb-2 teal--text text-uppercase">
                                                <span style="font-weight: bolder">
                                                    Dados do Projecto
                                                </span>
                                            </v-subheader>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content class="">
                                                    <v-list-item-title class="font-weight-bold">Nome</v-list-item-title>
                                                    <v-list-item-subtitle class="black--text">
                                                        Informação
                                                    </v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-sheet>
                                    </v-col>
                                </v-row>
                            </v-list>
                        </v-card>

                    </v-container>
                    <div style="flex: 1 1 auto"></div>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogDelete" v-model="dialogDelete" max-width="550px">
                <v-card>
                    <v-card-title class="text-h6">Tens a certeza que pretendes eliminar este
                        Bloco?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined color="red" dark @click="closeDelete">não</v-btn>
                        <v-btn rounded outlined color="#00897b" dark @click="deleteItemConfirm">sim</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
    </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";
import DatePicker from "vue-datepicker";
import Vue from "vue";

const gradients = [
    ["#222"],
    ["#42b3f4"],
    ["red", "orange", "yellow"],
    ["purple", "violet"],
    ["#00c6ff", "#F0F", "#FF0"],
    ["#f72047", "#ffd200", "#1feaea"],
];
export default {
    props: [
        "blocos",
        "gestores",
        "estadoblocos",
        "funcaoEquipe",
        "filtroGestores",
        "filtroProjectos",
        "categorias",
        "tipoProjectos",
    ],
    components: {
        AppLayout,
        DatePicker,
    },

    data() {
        return {

            dialog: false,
            dialogDetalhebloco: false,
            dialogDelete: false,
            bloco: {},
            query: {},
            deletedIndex: -1,
            input: 1,
            editedIndex: -1,
            erros: [],
            itemsPerPageArray: [8, 16, 24, 32],
            page: 1,
            itemsPerPage: 8,
            sortBy: "",
            sortDesc: false,
            search: "",
            filter: {},
            dataFinalFiltrolRules: {
                Valido(value, data) {
                    return (
                        value >= data ||
                        "A data final não pode ser inferior que a data inicial"
                    );
                },
            },
            defaultbloco: {
                arquivos: "",
            },

            nomeblocoRules: [(v) => !!v || "Campo obrigatório!"],
            dataFimRealRules: {
                Valido(value, data) {
                    return (
                        value > data ||
                        "A data de Final tem de ser maior que a data! dados"
                    );
                },
            },
            dataInicioRules: [
                (v) => !!v || "Data Obrigatório",
            ],
        };
    },
    watch: {

    },
    created() {
    },

    computed: {
        numberOfPages() {
            return Math.ceil(this.blocos.length / this.itemsPerPage);
        },

    },

    methods: {
        tarefasProjecto(item) {
            this.$inertia.get(
                "/blocos/apartamento/" + btoa(btoa(btoa(item)))
            );
        },
        dialogoDetalhebloco() {
            this.dialogDetalhebloco = false;
            this.show2 = "";
        },
        validate() {
            this.$refs.formbloco.validate();
        },
        nextPage() {
            if (this.page + 1 <= this.numberOfPages) this.page += 1;
        },
        formerPage() {
            if (this.page - 1 >= 1) this.page -= 1;
        },

        updateItemsPerPage(number) {
            this.itemsPerPage = number;
        },

        redirect: function (documento) {
            window.open("/storage/" + documento, "_blank");
        },

        carregarDialog() {
            this.bloco = Object.assign({}, this.defaultbloco);
            this.editIndex = -1;
            this.dialog = true;
        },
        editItem(item) {
            this.editedIndex = this.blocos.indexOf(item);
            this.bloco = Object.assign({}, item);
            this.dialog = true;
            //Percorrer o item selecionado
            item.control_projecto_tecnologia.forEach((elemento) => {
                this.projectoTecnologia.push(elemento.tecnologia);
            });
            //Percorrer a v-modal BackEnd
            this.projectoTecnologia.forEach((element) => {
                this.backEnd.push(element.id);
            });
        },
        deleteItem(item) {
            this.editedIndex = this.blocos.indexOf(item);
            this.bloco = Object.assign({}, item);
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.bloco = Object.assign({}, this.defaultbloco);
                this.editedIndex = -1;
            });
        },

        deleteItemConfirm() {
            this.$inertia.delete("/projectos/projecto/" + this.bloco.id, {
                onFinish: () => {
                    Vue.toasted.global.defaultSuccess({
                        msg: " " + this.$page.props.flash.success,
                    });
                    this.closeDelete();
                },
            });
        },

        verDetalhe(item) {
            this.bloco = Object.assign({}, item);
            this.equipeProjectos(item);
            this.dialogDetalhebloco = true;
        },
        cancelarDialog() {
            this.bloco = Object.assign({}, this.defaultbloco);
            this.editedIndex = -1;
            this.dialog = false;
        },

        closeSave() {
            this.bloco = Object.assign({}, this.defaultbloco);
            this.editedIndex = -1;
            this.dialog = false;
        },

        pdf: function (item) {
            this.bloco = Object.assign({}, item);
            this.window.open(
                "/blocos/arquivo-pdf-projecto" + this.bloco.id
            );
        },
        save() {


            if (this.$refs["formbloco"].validate()) {
                if (this.editedIndex > -1) {
                    this.$inertia.post(
                        "/projectos/projecto-atualizar",
                        this.bloco,
                        {
                            onFinish: () => {
                                if (this.$page.props.flash.success != null) {
                                    Vue.toasted.global.defaultSuccess({
                                        msg:
                                            "" + this.$page.props.flash.success,
                                    });
                                }
                                if (this.$page.props.flash.error != null) {
                                    Vue.toasted.global.defaultError({
                                        msg:
                                            "" + this.$page.props.flash.success,
                                    });
                                }
                                this.closeSave();
                            },
                        }
                    );
                } else {
                    this.$inertia.post("/blocos/bloco", this.bloco, {
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
                            this.closeSave();
                        },
                    });
                }
            }
        },

        // Adicionar responsaveis


        filtrarData() {
            if (this.query.data_inicial < this.query.data_final) {
                this.filtrarEstado();
            }
        },

        filtrarEstado() {
            axios
                .get("/projectos/filtrar-estado", {
                    params: this.query,
                })
                .then((response) => {
                    this.blocos = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },

    },

};
</script>

<style>
.corprincipal {
    background-color: #0e85a3 !important;
}

.listabloco {
    color: #000 !important;
    font-weight: bold;
    font-size: 1rem;
}
</style>
