<template>
  <app-layout>
    <v-container>
      <v-row>
        <v-col cols-12 sm="5" md="3">
          <v-card elevation="0">
            <h3 class="black--text text-left font-weight-bold pa-2">
              Usuário :({{ this.usuarios.length }})
            </h3>
          </v-card>
        </v-col>
      </v-row>
      <v-card class="my-2">
        <template>
          <v-card-title>
            <v-col cols="11">
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Pesquisar Usuários"
                single-line
                hide-details
                class="my-5"
              >
              </v-text-field>
            </v-col>
            <!-- <v-spacer class="" vertical></v-spacer> -->
            <div class="">
              <v-btn
                bottom
                color="#00897B"
                class=""
                elevation="3"
                dark
                @click="carregarDialog()"
              >
                <v-icon>add </v-icon>
              </v-btn>
              <!-- <v-btn
                  color="red"
                  dark
                  class=""
                  :href="'funcionario/meus-dados/'"
                  target="_blank"
                >
                  <v-icon title="Export em pdf">mdi-file-pdf-box</v-icon>
                </v-btn> -->
              <!-- <v-btn color="green" dark class="" :href="'funcionario/meus-dados/'" target="_blank">
                  <v-icon title="Export em excel">mdi-file-excel</v-icon>
                </v-btn> -->
            </div>
          </v-card-title>
          <v-card-text>
            <v-data-table
              :headers="headers"
              :items="usuarios"
              :search="search"
              sort-by="name"
            >
              <template v-slot:item.numero="{ item, index }">
                <tr>
                  <td>{{ index + 1 }}</td>
                </tr>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-icon
                  small
                  class="mr-2"
                  @click="editedItem(item)"
                  color="blue"
                  :disabled="!user.can['editar usuario']"
                >
                  mdi-pencil
                </v-icon>
                <v-icon
                  small
                  @click="deleteItem(item)"
                  color="red"
                  :disabled="!user.can['delete usuario']"
                >
                  mdi-delete
                </v-icon>
                <v-icon
                  small
                  @click="verDetalhe(item)"
                  color="green"
                  :disabled="!user.can['ver usuario']"
                >
                  visibility
                </v-icon>
                <v-btn
                  color="grey"
                  @click="verPermissao(item)"
                  icon
                  title="atribuir permissão"
                >
                  <v-icon small> perm_identity </v-icon>
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </template>
      </v-card>

      <v-dialog v-if="dialog" v-model="dialog" max-width="700px" persistent>
        <v-card>
          <v-toolbar elevation="2" class="text-uppercase font-weight-bold">
            <v-toolbar-title>{{
              editedIndex > -1 ? "Actualizar usuário" : "Cadastrar usuário"
            }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-divider></v-divider>
          <v-form ref="form" lazy-validation>
            <v-container>
              <v-card-text>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="usuario.name"
                      label="Nome Completo do Usuario"
                      hide-details="auto"
                      prepend-icon="person"
                      :rules="nomeCompletoRules"
                      :error-messages="erros.name"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="usuario.username"
                      label="User name"
                      hide-details="auto"
                      prepend-icon="person"
                      :disabled="editedIndex == -1"
                      v-if="userNameCompletoRules"
                      :error-messages="erros.username"
                    ></v-text-field>
                  </v-col>
                  <!-- <v-col cols="6" sm="12" md="6">
                      <v-text-field v-model="usuario.username" label="Nome de Utilizador" prepend-icon="person">
                      </v-text-field>
                    </v-col> -->
                </v-row>
                <v-row>
                  <v-col cols="6" sm="12" md="6">
                    <v-text-field
                      label="Email"
                      v-model="usuario.email"
                      hide-details="auto"
                      prepend-icon="alternate_email"
                      :rules="emailRules"
                      :error-messages="erros.email"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" sm="12" md="6">
                    <v-text-field
                      v-model="usuario.telefone"
                      label="Numero de telefone"
                      :rules="telefoneRules"
                      :error-messages="erros.telefone"
                      prepend-icon="phone_in_talk"
                    >
                    </v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" sm="12" md="12">
                    <v-autocomplete
                      v-model="usuario.roles"
                      prepend-icon="assignment_ind"
                      dense
                      :rules="funcaoRules"
                      :error-messages="erros.funcao"
                      required
                      label="Função"
                      item-value="name"
                      item-text="name"
                      :items="roles"
                    >
                    </v-autocomplete>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions class="justify-end">
                <v-btn color="red" dark @click="cancelarDialog()">Cancelar</v-btn>
                <v-btn color="#00897B" dark @click="save()">{{
                  editedIndex > -1 ? "atualizar" : "adicionar"
                }}</v-btn>
              </v-card-actions>
            </v-container>
          </v-form>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogDeleteUsuario" max-width="500px">
        <v-card>
          <v-card-title class="text-h6"
            >Tens a certeza que desajas eliminar?</v-card-title
          >
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" dark @click="closeDelete">não</v-btn>
            <v-btn color="#00897B" dark @click="deleteItemConfirm">sim</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-row>
        <v-dialog
          v-if="dialogDetalheUsuario"
          v-model="dialogDetalheUsuario"
          fullscreen
          hide-overlay
          transition="dialog-bottom-transition"
        >
          <v-card>
            <v-card-title class="corprincipal">
              <v-spacer></v-spacer>
              <v-toolbar-title class="text-white">Detalhes dos Usuarios</v-toolbar-title>
              <v-btn icon dark @click="dialogDetalheUsuario = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-card class="mt-12">
                  <v-subheader class="corprincipal font-weight-regular white--text">
                    <span style="font-weight: bolder">
                      DADOS PESSOAIS DO RESPONSAVEL</span
                    >
                  </v-subheader>
                  <v-row class="mx-auto text-left">
                    <v-col cols="3">
                      <div class="font-weight-normal">
                        <strong>Nome do Usuario</strong><br />
                        {{ usuario.name }}
                        <!-- <br /><br />


                        <strong>Nº identificação</strong><br />
                        {{

                        }} -->
                      </div>
                    </v-col>
                    <v-col cols="3">
                      <div class="font-weight-normal">
                        <strong>Nome de Utilizador </strong><br />
                        {{ usuario.username }}
                        <!-- <br /><br />
                        <br /><br />
                        <strong>Data de emissao</strong><br />
                        {{


                        }} -->
                      </div>
                    </v-col>
                    <v-col cols="3">
                      <div class="font-weight-normal">
                        <strong>Email</strong><br />
                        {{ usuario.email }}
                        <!-- <br /><br />
                        <strong>Data de validade</strong><br />
                        {{


                        }} -->
                      </div>
                    </v-col>
                    <v-col cols="3">
                      <div class="font-weight-normal">
                        <strong>Terminal</strong><br />
                        {{ usuario.telefone }}
                        <br /><br />
                      </div>
                    </v-col>
                  </v-row>

                  <!-- <v-row class="mx-auto text-left">
                    <v-col cols="4"> </v-col>
                  </v-row>  <v-subheader class="font-weight-regular primary white--text"><span style="font-weight: bolder">DADOS
                      DO PROJECTO

                    </span></v-subheader>
                  <v-row class="mx-auto text-left">
                    <v-col cols="4">
                      <div class="font-weight-normal">
                        <strong>Função</strong><br />
                        {{}}
                      </div>
                    </v-col>

                  </v-row>
                  <v-row class="mx-auto text-left">
                    <v-col cols="4"> </v-col>
                  </v-row> -->
                  <!-- <v-card v-if="usuario.length>0">


                  <v-subheader class="font-weight-regular primary white--text"><span style="font-weight: bolder" >DADOS DO PROJETOS

                    </span></v-subheader>
                  <v-divider />

                  <v-data-table :headers="headersUsuarioDetalhes" :items="usuario" hide-default-footer>
                  </v-data-table>
                </v-card> -->
                </v-card>
              </v-container>
            </v-card-text>
            <div style="flex: 1 1 auto"></div>
          </v-card>
        </v-dialog>
      </v-row>

      <v-dialog
        v-if="dialog_visualizar"
        v-model="dialog_visualizar"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
      >
        <v-card tile>
          <v-card-title class="corprincipal">
            <v-btn icon dark @click="dialog_visualizar = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title class="text-white">Detalhes do Utilizador</v-toolbar-title>

            <v-spacer></v-spacer>
            <v-toolbar-items></v-toolbar-items>
            <v-menu bottom right offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item>
                  <v-list-item-title>Menu1</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-card class="mt-12">
                <v-alert
                  v-if="roles.length > 0 && $page.props.flash.success"
                  text
                  type="success"
                  >{{ $page.props.flash.success }}</v-alert
                >
                <v-alert
                  v-if="roles.length > 0 && $page.props.flash.error"
                  text
                  type="error"
                  >{{ $page.props.flash.error }}</v-alert
                >
              </v-card>

              <v-card class="mt-12">
                <template>
                  <v-card>
                    <v-toolbar flat color="#00897B" dark>
                      <v-app-bar-nav-icon></v-app-bar-nav-icon>
                      <v-toolbar-title class="text-uppercase text-center mt-4"
                        ><h5>
                          ASSOCIAR FUNÇÕES VS PERMISSÕES AO UTILIZADOR &nbsp;<span
                            v-if="usuario_permissao"
                            style="font-weight: bolder"
                            >{{ usuario_permissao.name }}</span
                          >
                        </h5></v-toolbar-title
                      >
                    </v-toolbar>

                    <v-tabs vertical>
                      <v-tab>
                        <v-icon left> mdi-account </v-icon>
                        Funções&nbsp;&nbsp;&nbsp;&nbsp;
                      </v-tab>
                      <v-tab>
                        <v-icon left> mdi-lock </v-icon>
                        Permissões
                      </v-tab>

                      <!--Funções-->
                      <v-tab-item>
                        <v-card flat>
                          <v-card-text>
                            <v-data-table
                              :headers="headersRoles"
                              :items="filtrerFuncoes"
                              sort-by="name"
                              class="elevation-1"
                            >
                            <template v-slot:item.created_at="{ item }">
                        <span>{{ item.created_at | formatDate }}</span>
                      </template>
                      <template v-slot:item.updated_at="{ item }">
                        <span>{{ item.updated_at | formatDate }}</span>
                      </template>
                              <template v-slot:top>
                                <v-toolbar flat color="white">
                                  <v-toolbar-title
                                    >Total({{ roles.length }})</v-toolbar-title
                                  >
                                  <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                    md="6"
                                  ></v-divider>
                                  <v-text-field
                                    v-model="busca_roles"
                                    append-icon="search"
                                    label="Procurar"
                                    single-line
                                    hide-details
                                  >
                                  </v-text-field>
                                  <div class="text-right mb-6">
                                    <v-btn
                                      color="#00897B"
                                      dark
                                      class="mb-2"
                                      @click="concederFuncoes"
                                      >ACTUALIZAR</v-btn
                                    >
                                  </div>
                                </v-toolbar>
                              </template>

                              <template v-slot:item.actions="{ item }">
                                <div class="d-flex">
                                  <v-checkbox
                                    v-model="user_roles"
                                    :value="item.name"
                                    color="#00897b"
                                  ></v-checkbox>
                                </div>
                              </template>
                            </v-data-table>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <!--Permissões-->
                      <v-tab-item>
                        <v-card flat>
                          <v-card-text>
                            <v-data-table
                              :headers="headersPermissions"
                              :items="filtrerPermissoes"
                              item-key="name"
                              sort-by="name"
                              class="elevation-1"
                            >
                            <template v-slot:item.created_at="{ item }">
                        <span>{{ item.created_at | formatDate }}</span>
                      </template>
                      <template v-slot:item.updated_at="{ item }">
                        <span>{{ item.updated_at | formatDate }}</span>
                      </template>
                              <template v-slot:top>
                                <v-toolbar flat color="white">
                                  <v-toolbar-title
                                    >Total({{ permissions.length }})</v-toolbar-title
                                  >
                                  <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                    md="6"
                                  ></v-divider>

                                  <v-text-field
                                    v-model="busca_permission"
                                    append-icon="search"
                                    label="Procurar"
                                    single-line
                                    hide-details
                                  >
                                  </v-text-field>
                                  <div class="text-right mb-6">
                                    <v-btn
                                      color="#00897B"
                                      dark
                                      class="mb-2"
                                      @click="concederPermissoes"
                                      >ACTUALIZAR</v-btn
                                    >
                                  </div>
                                </v-toolbar>
                              </template>

                              <template v-slot:item.actions="{ item }">
                                <div class="d-flex">
                                  <v-checkbox
                                    color="#00897b"
                                    v-model="user_permissions"
                                    :value="item.name"
                                  ></v-checkbox>
                                </div>
                              </template>
                            </v-data-table>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>
                    </v-tabs>
                  </v-card>
                </template>
              </v-card>
            </v-container>
          </v-card-text>

          <div style="flex: 1 1 auto"></div>
        </v-card>
      </v-dialog>
      <!-- <v-row>
          <v-col cols="12" md="4">
            <v-btn bottom color="#00897B" dark fab fixed right @click="carregarDialog()">
              <v-icon>add </v-icon>
            </v-btn>
          </v-col>
        </v-row> -->
    </v-container>
  </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";

const gradients = [
  ["#222"],
  ["#42b3f4"],
  ["red", "orange", "yellow"],
  ["purple", "violet"],
  ["#00c6ff", "#F0F", "#FF0"],
  ["#f72047", "#ffd200", "#1feaea"],
];
export default {
  props: ["responsavel", "funcao", "usuarios", "funcoes", "roles", "permissions"],
  components: {
    AppLayout,
  },

  data() {
    return {
      usuario: {
        roles: {
          name:"",
          id:null
        },
        name:"",
      },
      defaultusuario: {},
      editedIndex: -1,
      dialog: false,
      dialogDetalheUsuario: false,
      panel_motivo: false,
      dialogDeleteUsuario: false,
      search: "",
      erros: [],
      user_roles: [],
      usuario_permissao: {},
      user_permissions: [],
      busca_roles: null,
      busca_permission: null,
      dialog_visualizar: false,
      headers: [
        {
          text: "Nº",
          value: "numero",
          sortable: false,
          class: "font-weight-bold black--text teal lighten-5 subtitle-1",
        },
        {
          text: "Nome do Usuario",
          value: "name",
          align: "start",
          sortable: true,
          class: "font-weight-bold black--text teal lighten-5 subtitle-1",
        },
        // { text: 'Nome de ultilizador', value: 'username', align: 'start', sortable: false, class:"font-weight-bold black white--text my-3 text-right",},
        {
          text: "Email",
          value: "email",
          class: "font-weight-bold black--text teal lighten-5 subtitle-1",
        },
        {
          text: "Terminal",
          value: "telefone",
          class: "font-weight-bold black--text teal lighten-5 subtitle-1",
        },
        {
          text: "Opções",
          value: "actions",
          sortable: false,
          class: "font-weight-bold black--text teal lighten-5 subtitle-1",
        },
      ],
      // headersUsuarioDetalhes: [
      //   {
      //     text: 'Nome do projecto', align: 'start', sortable: true, value: 'nome_proj', class: "font-weight-bold black--text"
      //   },
      //   { text: 'Estado do Projeto', value: 'status_proj', class: "font-weight-bold black--text" },
      //   {
      //     text: 'Data de criação do Projecto', value: 'dat_inicio_real', class: "font-weight-bold black--text"
      //   },

      // ],

      headersRoles: [
        {
          text: "Função",
          align: "start",
          sortable: false,
          value: "name",
          class: "teal lighten-4  darken-4 white--text",
        },
        {
          text: "Data de Criação",
          value: "created_at",
          class: "teal lighten-4  darken-4 white--text",
        },
        {
          text: "Data de Modificação",
          value: "updated_at",
          class: "teal lighten-4  darken-4 white--text",
        },
        {
          text: "Estado",
          value: "actions",
          sortable: false,
          class: "teal lighten-4  darken-4 white--text",
        },
      ],

      headersPermissions: [
        {
          text: "Permissão",
          align: "start",
          sortable: false,
          value: "name",
          class: "teal lighten-4  darken-4 white--text",
        },
        {
          text: "Data de Criação",
          value: "created_at",
          class: "teal lighten-4 darken-4 white--text",
        },
        {
          text: "Data de Modificação",
          value: "updated_at",
          class: "teal lighten-4  darken-4 white--text",
        },
        {
          text: "Estado",
          value: "actions",
          sortable: false,
          class: "teal lighten-4  darken-4 white--text",
        },
      ],
      //Area de Validação dos campos
      nomeCompletoRules: [
        (v) => !!v || "Campo do nome é Obrigatório",
        (v) => (v && v.length >= 1) || "O nome Completo tem que ter pelomenos 7 dígitos",
      ],
      userNameCompletoRules: [
        (v) => !!v || "Campo do nome é Obrigatório",
        (v) => (v && v.length >= 7) || "O nome Completo tem que ter pelomenos 7 dígitos",
      ],
      senhaRules: [
        (v) => !!v || "Campo vazio",
        (v) => (v && v.length >= 8) || "A descrição da senha é muito curta",
      ],
      emailRules: [
        (v) => !!v || "E-mail Obrigatório",
        (v) => /.+@.+\..+/.test(v) || "E-mail Inválida",
      ],
      telefoneRules: [
        (v) => !!v || "Telefone é Obrigatório",
        (v) =>
          (v && v.length <= 12 && v.length >= 9) ||
          "O Numero deve ter 9 digitos no Minino",
      ],
      funcaoRules: [(v) => !!v || "Campo Obrigatorio"],
      //
    };
  },

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    filtrerFuncoes() {
      if (this.busca_roles) {
        let result = this.roles.filter((item) => {
          return (
            item.name.toLowerCase().match(this.busca_roles) ||
            item.created_at.toLowerCase().match(this.busca_roles) ||
            item.id == this.busca_roles
          );
        });

        return result ? result : [];
      } else {
        return this.roles;
      }
    },
    filtrerPermissoes() {
      if (this.busca_permission) {
        let result = this.permissions.filter((item) => {
          return (
            item.name.toLowerCase().match(this.busca_permission) ||
            item.created_at.toLowerCase().match(this.busca_permission) ||
            item.id == this.busca_permission
          );
        });

        return result ? result : [];
      } else {
        return this.permissions;
      }
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
      this.$refs.form.validate();
    },

    carregarDialog() {
      this.usuario = Object.assign({}, this.defaultusuario);
      this.editedIndex = -1;
      this.dialog = true;
    },
    editedItem(item) {
      this.editedIndex = this.usuarios.indexOf(item);
      this.usuario = Object.assign({}, item);
      this.dialog = true;
    },

    cancelarDialog() {
      this.usuario = Object.assign({}, this.defaultusuario);
      this.editedIndex = -1;
      this.dialog = false;
    },
    verDetalhe(item) {
      this.usuario = Object.assign({}, item);
      this.dialogDetalheUsuario = true;
    },
    deleteItem(item) {
      this.editedIndex = this.usuarios.indexOf(item);
      this.usuario = Object.assign({}, item);
      this.dialogDeleteUsuario = true;
    },
    closeDelete() {
      this.dialogDeleteUsuario = false;
      this.$nextTick(() => {
        this.usuario = Object.assign({}, this.defaultusuario);
        this.editedIndex = -1;
      });
    },

    deleteItemConfirm() {
      this.$inertia.delete("/users/user/" + this.usuario.id, {
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
          this.closeDelete();
        },
      });
    },
    closeSave() {
      this.usuario = Object.assign({}, this.defaultusuario);
      this.editedIndex = -1;
      this.dialog = false;
    },

    save() {
      if (this.$refs["form"].validate()) {
        if (this.editedIndex > -1) {
          this.$inertia.put("/users/user/" + this.usuario.id, this.usuario, {
            onFinish: () => {
              Vue.toasted.global.defaultSuccess({
                msg: " " + this.$page.props.flash.success,
              });
              this.closeSave();
            },
          });
        } else {
          this.$inertia.post("/users/user", this.usuario, {
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

    verPermissao(item) {
      this.dialog_visualizar = true;
      this.usuario_permissao = item;
      item.roles.forEach((role) => {
        this.user_roles.push(role.name);
      });

      item.permissions.forEach((permission) => {
        this.user_permissions.push(permission.name);
      });
    },

    concederFuncoes() {
      this.loading = true;
      // this.$inertia.put('/permission/concederFuncoes/' + this.user.id,{user_roles: this.user_roles,})
      this.$inertia.visit("/permission/concederFuncoes/" + this.usuario_permissao.id, {
        method: "put",
        data: { user_roles: this.user_roles },
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
          setTimeout(() => {
            this.loading = false;
            this.$inertia.reload(
              "/permission/concederFuncoes/" + this.usuario_permissao.id
            );
          }, 100);
          this.user_roles = [];
        },
      });
    },

    concederPermissoes() {
      this.loading = true;
      alert(this.user_permissions);
      // this.$inertia.put('/permission/concederPermissoes/' + this.usuario_permissao.id,{user_permissions: this.user_permissions,})
      this.$inertia.visit("/permission/concederPermissoes/" + this.usuario_permissao.id, {
        method: "put",
        data: { user_permissions: this.user_permissions },
        onFinish: () => {
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
            this.user_roles = [];
          },
            setTimeout(() => {
              this.loading = false;
              this.$inertia.reload(
                "/permission/concederPermissoes/" + this.usuario_permissao.id
              );
            }, 100);
          this.user_permissions = [];
        },
      });
    },
  },
};
</script>

<style>
table {
  font-weight: bold;
}
.corprincipal {
  background-color: #00897b !important;
}
@import "vuetify/dist/vuetify.min.css";
</style>
