<template>
  <app-layout>
    <div class="dashboard">
      <v-row>
        <v-col cols-12 sm="6" md="4">
          <v-card elevation="0">
            <h3 class="black--text text-left font-weight-bold pa-2 text-upercase">
              Notificações
            </h3>
            <!-- {{ notificacoes }} -->
          </v-card>
        </v-col>
      </v-row>

      <v-container>
        <v-template v-if="novoEstado == true">
          <v-card class="mb-15 pa-0">
            <v-toolbar dark color="#00897B" elevation="5">
              <v-toolbar-title>Enviar Notificações</v-toolbar-title>
            </v-toolbar>

            <v-form ref="form" lazy-validation>
              <v-row dense class="px-4 py-0">
                <v-col cols="12" sm="6" md="6">
                  <v-checkbox
                    label="Enviar para Menbro  de Equipe"
                    value="membro"
                    v-model="notificacao.mebro_grupo"
                    @click="vizualizar = false"
                  >
                  </v-checkbox>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-checkbox
                    label="Enviar para Grupo de Projecto "
                    value="grupo_projecto"
                    v-model="notificacao.mebro_grupo"
                    @click="vizualizar = true"
                  >
                  </v-checkbox>
                </v-col>
              </v-row>
              <v-divider></v-divider>

              <v-row v-if="!vizualizar" dense class="px-7 py-0">
                <v-col cols="12" sm="6" md="4">
                  <v-checkbox
                    label="Todos Membros"
                    value="Todos"
                    v-model="notificacao.membro"
                    @click="vizualizargrupos = false"
                  >
                  </v-checkbox>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                  <v-checkbox
                    label="Alguns Membros"
                    value="Alguns"
                    v-model="notificacao.membro"
                    @click="vizualizargrupos = true"
                  >
                  </v-checkbox>
                </v-col>

                <v-col cols="12" sm="6" md="4" v-if="vizualizargrupos">
                  <v-autocomplete
                    multiple
                    v-model="notificacao.responsavel_id"
                    item-text="nome_responsavel"
                    item-value="id"
                    :items="responsavel"
                    label="Membros de equipe"
                  >
                  </v-autocomplete>
                </v-col>
              </v-row>

              <v-row v-if="vizualizar" dense class="px-7 py-0">
                <v-col cols="12" sm="6" md="4">
                  <v-checkbox
                    label="Todos"
                    value="Todos"
                    v-model="notificacao.grupo"
                    @click="vizualizarmembros = false"
                  >
                  </v-checkbox>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-checkbox
                    label="Alguns"
                    value="Alguns"
                    v-model="notificacao.grupo"
                    @click="vizualizarmembros = true"
                  >
                  </v-checkbox>
                </v-col>

                <v-col cols="12" sm="6" md="4" v-if="vizualizarmembros">
                  <v-autocomplete
                    v-model="notificacao.projecto_id"
                    item-text="nome_proj"
                    item-value="id"
                    :items="projectos"
                    label="Grupo de Projectos"
                    multiple
                  >
                  </v-autocomplete>
                </v-col>
              </v-row>
              <v-divider></v-divider>
              <v-card-text>
                <v-textarea
                  rows="2"
                  v-model="notificacao.designacao"
                  label="Enviar
                  notificação"
                  hide-details="auto"
                  :rules="descricaofuncaoRuls"
                  :error-messages="erros.descricao"
                  prepend-icon="folder"
                ></v-textarea>
              </v-card-text>
            </v-form>
            <v-card-actions class="justify-end">
              <v-btn dark color="red" @click="closeSave()">Cancelar</v-btn>
              <v-btn dark color="#00897B" @click="save()">{{ "enviar" }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-template>
        <v-card>
          <template>
            <v-card-title>
              <v-col md="2">
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Pesquisar notificação"
                  single-line
                  hide-details
                  class="text-green"
                  dense
                >
                </v-text-field>
              </v-col>

              <v-spacer></v-spacer>
              <v-divider inset vertical></v-divider>
              <v-btn
                right
                dense
                dark
                color="#00897B"
                @click="estado()"
                v-if="abrirestado == true"
                >Adicionar nova notificação</v-btn
              >
            </v-card-title>
            <v-card-text>
              <v-data-table :headers="headers" :items="notificacoes" :search="search" :sort-by="responsavel">
                <template v-slot:item.responsavel="{ item }">
                  <span v-if="item.responsaveis"
                    >Enviado por: {{ item.responsaveis.nome_responsavel }}</span
                  >
                  <span v-else> SMS do Sistema</span>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-icon
                    color="green"
                    icon
                    small
                    @click="verDetalhe(item)"
                    title="Visualizar detalhes"
                  >
                    visibility
                  </v-icon>
                </template>
                <!-- <template v-slot:item.emissor="{ item }"> Envia do por : </template> -->
                <!-- <template v-slot:item.actions="{ item }">
                  <v-icon small class="mr-2 blue--text" @click="editItem(item)">
                    mdi-pencil
                  </v-icon>
                  <v-icon small class="red--text" @click="deleteItem(item)">
                    mdi-delete
                  </v-icon>
                </template> -->
              </v-data-table>
            </v-card-text>
          </template>
        </v-card>
      </v-container>
      <v-dialog v-if="dialogDelete" v-model="dialogDelete" max-width="500px">
        <v-card>
          <v-card-title class="text-h4"
            >Tens a certeza que pretendes eliminar?</v-card-title
          >
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red dark" @click="closeDelete()">não</v-btn>
            <v-btn color="#00897B" dark @click="deleteItemConfirm()">sim</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog
          v-model="dialognotificacao"
          transition="dialog-top-transition"
          max-width="500"
        >
          <template v-slot:default="dialog">
            <v-card>
              <v-toolbar class="text-h5 pa-4" color="#00897b" dark
                >Notificação ({{ notificacao.estado_notificacao.designacao }})</v-toolbar
              >
              <v-card-text><br/>
                Descrição<br/>
                <div class="text-h6 pa-5">
                  {{ notificacao.descricao }}<br /><br />
                </div>
                <div class="subtitle-2 pa-4">
                  Enviado por: {{ notificacao.responsaveis.name }}<br />
                  Data envio: {{ notificacao.created_at }}<br />
                </div>
              </v-card-text>
              <v-card-actions class="justify-end">
                <v-btn text @click="dialog.value = false">Close</v-btn>
                <!-- <v-btn text @click="notificaca_lida()">Visualizar</v-btn> -->
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";
export default {
  props: ["notificacoes", "funcoes", "responsavel", "projectos"],
  components: {
    AppLayout,
  },

  data() {
    return {
      erros: [],
      notificacao: {
        mebro_grupo: "membro",
        membro: "Alguns",
        grupo: "Alguns",
      },
      defaultnotificacao: {
        mebro_grupo: "membro",
        membro: "Alguns",
        grupo: "Alguns",
      },

      dialognotificacao: false,
      dialogDelete: false,
      dialog: false,
      search: "",
      vizualizarmembros: true,
      vizualizar: false,
      vizualizargrupos: true,
      editedIndex: -1,
      novoEstado: false,
      abrirestado: true,
      descricaofuncaoRuls: [(v) => !!v || "Campo Obrigatório"],
      query: {
        funcao_id: null,
      },

      headers: [
        // { text: "Nº", value: "id", class: "font-weight-blod  py-7 ",  sortable: true,},
        {
          text: "",
          value: "responsavel",
          // sortable: false,
          class: "font-weight-blod ",
        },
        {
          text: "Descrição da notificação",
          value: "designacao",
          align: "start",
          class: "font-weight-blod py-7",
        },
        {
        text: "Estado",
          value: "estado_notificacao.designacao",
          align: "start",
          class: "font-weight-blod py-7",
        },
        {  text: "Data de envio",
          value: "created_at",
        // sortable: false,
        class: "font-weight-blod " },
        {
          text: "visualizar",
          value: "actions",
        // sortable: false,
        class: "font-weight-blod " },
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
    verDetalhe(item) {
      //this.notificaca_lida(item);
      this.notificacao = Object.assign({}, item);
      this.dialognotificacao = true;
    },
    notificaca_lida(item) {
      this.notificacao = Object.assign({}, item);
     //// this.$inertia.get(
    //    `/notificacoes/notificaca-lida/${btoa(btoa(btoa(this.notificacao.id)))}`
      //  `/notificacoes/notificaca-lida/${this.notificacao.id}`,
     //   this.notificacao
   //   );
      // axios
      //   .get("/notificacoes/notificaca-lida", {
      //     params:this.noti,
      //   })
      //   .then((response) => {

      //   })
      //   .catch((error) => {
      //     //toastr.warning('Houve uma falha ao carregar os dados!...');
      //   });
      this.dialognotificacao = false;
    },
    // carregarDialog() {
    //   this.notificacao = Object.assign({}, this.defaultnotificacao);
    //   this.editedIndex = -1;
    //   this.dialog = true;
    // },
    editItem(item) {
      this.editedIndex = this.notificacoes.indexOf(item);
      this.notificacao = Object.assign({}, item);
      this.dialog = true;
      this.abrirestado = false;
      this.novoEstado = true;
    },
    deleteItem(item) {
      this.editedIndex = this.notificacoes.indexOf(item);
      this.notificacao = Object.assign({}, item);
      this.dialogDelete = true;
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.notificacao = Object.assign({}, this.defaultnotificacao);
        this.editedIndex = -1;
      });
    },

    deleteItemConfirm() {
      this.$inertia.delete("/noticacoes/notificacao/" + this.notificacao.id, {
        onFinish: () => {
          Vue.toasted.global.defaultSuccess({ msg: "Operação realizada com sucesso" });
          this.closeDelete();
        },
      });
    },

    closeSave() {
      this.notificacao = Object.assign({}, this.defaultnotificacao);
      this.erros = [];
      this.novoEstado = false;
      this.abrirestado = true;
      this.vizualizar=false;
      this.vizualizargrupos=true;
    },

    filtrargrupoequipa() {
      axios
        .get("/notificacoes/fitrar-notificacao-grupo", {
          params: this.query,
        })
        .then((response) => {
          this.projetos = response.data;
        })
        .catch((error) => {
          //toastr.warning('Houve uma falha ao carregar os dados!...');
        });
    },


    save() {
      if (this.$refs["form"].validate()) {
        if (this.editedIndex > -1) {
          //rota agrupada onde o primeir é o prefixo e o segundo parametro é a rota do arquivo
          this.$inertia.put(
            `/notificacoes/notificacao/${this.notificacao.id}`,
            this.notificacao,
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
          this.$inertia.post("/notificacoes/notificacao", this.notificacao, {
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
          });
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
.v-data-table header {
  font-size: 14px !important;
}

.v-data-table th {
  font-size: 15px !important;
}

.v-data-table td {
  font-size: 14px !important;
  border-bottom: 0px solid !important;
}
</style>
