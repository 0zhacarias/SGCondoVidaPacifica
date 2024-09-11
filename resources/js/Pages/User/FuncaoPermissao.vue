<template>
  <app-layout>
    <v-container>
      <v-row>
        <v-col cols-12 sm="6" md="6">
          <v-card elevation="0">
            <h3 class="black--text text-left font-weight-bold pa-2 text-uppercase">
              Lista de Permissões & Funções
            </h3>
          </v-card>
        </v-col>
      </v-row>

      <!-- <v-alert v-if="$page.props.flash.success" text type="success">{{
        $page.props.flash.success
      }}</v-alert>
      <v-alert v-if="$page.props.flash.error" text type="error">{{
        $page.props.flash.error
      }}</v-alert> -->

      <v-row class="my-2">
        <!--v-col cols="12" sm="12">
          <div v-if="loading" class="text-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <v-alert dense text v-if="alert.type == 'success'" type="success">{{ alert.text }}</v-alert>
          <v-alert dense outlined v-if="alert.type == 'error'" type="error">
            <v-list-item v-for="texto in alert.text" :key="texto">
              <v-list-item-title v-for="msg in texto" :key="msg">{{ msg }}</v-list-item-title>
            </v-list-item>
          </v-alert>
        </|v-col-->

        <!-- <v-snackbar
          v-if="$page.props.flash.success"
          v-model="bar"
          top
          :timeout="4000"
          color="success"
        >
          {{ $page.props.flash.success }}
        </v-snackbar> -->

        <v-col cols="12" sm="12">
          <v-card>
            <v-toolbar class="text-uppercase font-weight-bold" elevation="1">
              <v-tabs v-model="tab" flat dark color="teal darken-4">
                <v-tab class="text-black">
                  <v-icon left color="teal darken-4">group</v-icon>Funções
                </v-tab>
                <v-tab class="text-black">
                  <v-icon left color="teal darken-4">edit</v-icon>Permissões
                </v-tab>
              </v-tabs>
            </v-toolbar>

            <v-tabs-items v-model="tab">
              <v-tab-item>
                <v-card flat>
                  <v-card-text>
                    <v-row>
                      <v-col cols="12" md="2">
                        <v-toolbar-title>Total({{ roles.length }})</v-toolbar-title>
                      </v-col>
                      <v-col cols="12" md="3">
                        <v-text-field
                          v-model="busca_roles"
                          append-icon="search"
                          label="Procurar"
                          single-line
                          hide-details
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-btn rounded outlined color="#00987b" dark class="mb-2 mx-4">
                          <inertia-link
                            class="text-white"
                            href="/permission/associar-funcoes-permissoes"
                          >
                            <v-icon>sync</v-icon>&nbsp;Atribuir Permissões
                          </inertia-link>
                        </v-btn>
                      </v-col>

                      <v-col cols="12" md="3">
                        <v-btn
                          color="#00987b"
                          dark
                          class="mb-2"
                          @click="dialog_add_role = true"
                        >
                          <v-icon>add</v-icon> Nova Função
                        </v-btn>
                      </v-col>
                    </v-row>
                    <v-data-table
                      :headers="headersRoles"
                      :items="filtrerFuncoes"
                      sort-by="name"
                      class="elevation-1"
                      :search="search"
                    >
                      <template v-slot:item.created_at="{ item }">
                        <span>{{ item.created_at | formatDate }}</span>
                      </template>
                      <template v-slot:item.updated_at="{ item }">
                        <span>{{ item.updated_at | formatDate }}</span>
                      </template>

                      <template v-slot:item.actions="{ item }">
                        <v-icon
                          small
                          @class="mr - 2"
                          @click="editItemRole(item)"
                          color="blue"
                          >mdi-pencil</v-icon
                        >
                        <v-icon small @click="deleteItemRole(item)" color="red"
                          >mdi-delete</v-icon
                        >

                      </template>
                      <template slot="no-data">
    sem nenhum dado
  </template>
  <template slot="no-results">
    não foi encontrado nehum dado na pesquisa
  </template>
                    </v-data-table>
                    <v-btn
                      bottom
                      color="#00987b"
                      dark
                      fab
                      fixed
                      right
                      @click="dialog_add_role = true"
                    >
                      <v-icon>add</v-icon>
                    </v-btn>
                  </v-card-text>
                  <!-- MODAL ADICIONAR FUNÇÃO-->
                  <v-dialog v-model="dialog_add_role" max-width="500px">
                    <template v-slot:activator="{ on }"> </template>
                    <v-form ref="form" lazy-validation>
                      <v-card>
                        <v-card-title
                          class="text-uppercase font-weight-bold"
                          elevation="2"
                        >
                          <span class="headline">{{ formTitleRole }}</span>
                        </v-card-title>

                        <v-card-text>
                          <v-container>
                            <v-row class="mx-2">
                              <v-col
                                class="align-center justify-space-between"
                                cols="12"
                                md="12"
                              >
                                <v-text-field
                                  v-model="role.name"
                                  :rules="rulesname"
                                  prepend-icon="perm_identity"
                                  placeholder="Função"
                                />
                              </v-col>
                            </v-row>
                          </v-container>
                        </v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn rounded outlined color="red darken-1" text @click="close()"
                            >Cancelar</v-btn
                          >
                          <v-btn rounded outlined color="teal darken-1" text @click="save()"
                            >Guardar</v-btn
                          >
                        </v-card-actions>
                      </v-card>
                    </v-form>
                  </v-dialog>
                  <!-- MODAL ELIMINAR -->
                  <v-dialog v-model="dialog_eliminar_role" max-width="400">
                    <v-card>
                      <v-card-title class="text-uppercase font-weight-bold" elevation="2">
                        <span class="headline">Eliminar Registo</span>
                      </v-card-title>

                      <v-card-text>
                        <br />
                        <h5 class="text-danger">Deseja eliminar este registo?</h5>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined 
                          color="red darken-1"
                          dark
                          @click="dialog_eliminar_role = false"
                          >Não</v-btn
                        >
                        <v-btn  rounded outlined color="blue darken-1" dark @click="confirmDeleteItemRole"
                          >Sim</v-btn
                        >
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-card>
              </v-tab-item>

              <v-tab-item>
                <v-card flat>
                  <v-card-text>
                    <v-row>
                      <v-col cols="12" md="3">
                        <v-toolbar-title
                          >Total Permission({{ permissions.length }})</v-toolbar-title
                        >
                      </v-col>
                      <!-- <v-divider class="mx-4" inset vertical></v-divider> -->
                      <v-col cols="12" md="2">
                        <v-text-field
                          v-model="busca_permission"
                          append-icon="search"
                          label="Procurar"
                          single-line
                          hide-details
                        >
                        </v-text-field>
                      </v-col>

                      <v-col cols="12" md="4">
                        <v-btn rounded outlined color="#00987b" dark class="mb-2 mx-3">
                          <inertia-link
                            class="text-white"
                            href="/permission/associar-funcoes-permissoes"
                          >
                            <v-icon>sync</v-icon>&nbsp;Atribuir Permissões
                          </inertia-link>
                        </v-btn>
                      </v-col>
                      <v-col cols="12" md="3">
                        <v-btn
                          color="#00987b"
                          dark
                          class="mb-2"
                          @click="dialog_add_permission = true"
                        >
                          <v-icon>add</v-icon> Nova Permissão
                        </v-btn>
                      </v-col>
                    </v-row>
                    <v-data-table
                      :headers="headersPermissions"
                      :items="filtrerPermissoes"
                      sort-by="name"
                      class="elevation-1"
                      :search="search"
                    >
                      <template v-slot:item.created_at="{ item }">
                        <span>{{ item.created_at | formatDate }}</span>
                      </template>
                      <template v-slot:item.updated_at="{ item }">
                        <span>{{ item.updated_at | formatDate }}</span>
                      </template>
                      <template v-slot:item.actions="{ item }">
                        <v-icon
                          small
                          @class="mr - 2"
                          @click="editItemPermission(item)"
                          color="blue"
                          >mdi-pencil</v-icon
                        >
                        <v-icon small @click="deleteItemPermission(item)" color="red"
                          >mdi-delete</v-icon
                        >
                      </template>
                      <template slot="no-data">
    sem nenhum dado
  </template>
  <template slot="no-results">
    não foi encontrado nehum dado na pesquisa
  </template>
                    </v-data-table>
                    <v-btn
                      bottom
                      color="#00987b"
                      dark
                      fab
                      fixed
                      right
                      @click="dialog_add_permission = true"
                    >
                      <v-icon>add</v-icon>
                    </v-btn>
                  </v-card-text>

                  <!-- MODAL ADICIONAR PERMISSÃO-->
                  <v-dialog v-model="dialog_add_permission" max-width="500px">
                    <v-card>
                      <v-form ref="form" lazy-validation>
                        <v-card-title
                          class="text-uppercase font-weight-bold"
                          elevation="2"
                        >
                          <span class="headline">{{ formTitlePermission }}</span>
                        </v-card-title>

                        <v-card-text>
                          <v-container>
                            <v-row class="mx-2">
                              <v-col
                                class="align-center justify-space-between"
                                cols="12"
                                md="12"
                              >
                                <v-text-field
                                  v-model="permission.name"
                                  :rules="rulesname"
                                  prepend-icon="mdi-lock"
                                  placeholder="Permissão"
                                />
                              </v-col>
                              <!-- 
                              <v-col cols="12" md="12">
                                <v-text-field :disabled="editedIndex == -1" prepend-icon="mdi-web" placeholder="Guard"
                                  v-model="permission.guard_name" :rules="rulesname" />
                              </v-col> -->
                            </v-row>
                          </v-container>
                        </v-card-text>
                      </v-form>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined color="red darken-1" text @click="close">Cancelar</v-btn>
                        <v-btn
                          color="teal darken-1"
                          text
                          type="submit"
                          @click="savePermissions()"
                          >Guardar</v-btn
                        >
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                  <!-- MODAL ELIMINAR -->
                  <v-dialog v-model="dialog_eliminar_permission" max-width="400">
                    <v-card>
                      <v-card-title class="corprincipal dark white--text">
                        <span class="headline">Eliminar Registo</span>
                      </v-card-title>

                      <v-card-text>
                        <br />
                        <h5 class="text-danger">Deseja eliminar este registo?</h5>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                          color="red darken-1"
                          text
                          @click="dialog_eliminar_permission = false"
                          >Não</v-btn
                        >
                        <v-btn
                          color="teal darken-1"
                          text
                          @click="confirmDeleteItemPermission"
                          >Sim</v-btn
                        >
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-card>
              </v-tab-item>
            </v-tabs-items>

            <v-card-actions>
              <v-spacer />
              <v-btn rounded outlined v-if="tab != 0" color="#00987b" text @click="tab -= 1">
                <v-icon>mdi-arrow-left</v-icon>Voltar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </app-layout>
</template>

<script>
import AppLayout from "../../Shared/AppLayout";
import Vue from "vue";
import { format, formatDistance } from "date-fns";

export default {
  props: ["msg", "roles", "permissions"],
  components: {
    AppLayout,
  },

  data() {
    return {
      bar: true,

      dialog: false,
      dialog_add_role: false,
      dialog_add_permission: false,
      dialog_eliminar_role: false,
      dialog_edit_role: false,
      dialog_edit_permission: false,
      dialog_eliminar_permission: false,
      dialog_visualizar: false,
      user_id: "",
      dialogPerfil: false,

      tab: 0,
      items: [
        { tab: "Funções", content: "Funções" },
        { tab: "Permissões", content: "Permissões" },
      ],
      total: 0,
      options: {},
      url: "",
      loading: false,
      alert: { text: "", type: "" },
      search: "",

      permission: { name: "", guard_name: "web" },
      role: { name: "", guard_name: "web" },

      user_roles: [],
      user_permissions: [],
      busca_roles: null,
      busca_permission: null,

      search_roles: "",
      search_permissions: "",

      headers: [
        {
          text: "ID",
          align: "start",
          sortable: true,
          value: "id",
        },
        {
          text: "Nome",
          align: "start",
          sortable: true,
          value: "name",
        },
        {
          text: "Username",
          align: "start",
          sortable: true,
          value: "username",
        },
        { text: "E-mail", value: "email" },
        { text: "Telefone", value: "telefone" },

        { text: "Funções", value: "roles", sortable: false },
        { text: "Opções", value: "actions", sortable: false },
      ],

      singleSelect: false,
      headersRoles: [
      {
        text: "Nº",
        align: "start",
        sortable: true,
        value: "id",
      },
        {
          text: "Função",
          align: "start",
          sortable: true,
          value: "name",
        },
        { text: "Data de Criação", value: "created_at" },
        { text: "Data de Modificação", value: "updated_at" },
        { text: "Ações", value: "actions", sortable: false },
      ],

      headersPermissions: [
      {
        text: "Nº",
        align: "start",
        sortable: false,
        value: "id",
      },
        {
          text: "Permissão",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "Data de Criação", value: "created_at" },
        { text: "Data de Modificação", value: "updated_at" },
        { text: "Ações", value: "actions", sortable: false },
      ],
      rulesname: [(v) => !!v || "Obrigatória."],

      foto: "",

      editedIndex: -1,
      estado: true,
      user_logado: {},
    };
  },

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    formTitleRole() {
      return this.editedIndex === -1 ? "Nova Função" : "Editar Função";
    },

    formTitlePermission() {
      return this.editedIndex === -1 ? "Nova Permissão" : "Editar Permissão";
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

  mounted() {},

  created() {
    setTimeout(() => {
      this.loading = false;
    }, 1000);
  },

  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    editItemRole(item) {
      this.editedIndex = this.roles.indexOf(item);
      this.role = Object.assign({}, item);

      this.dialog_add_role = true;
    },

    editItemPermission(item) {
      this.editedIndex = this.permissions.indexOf(item);
      this.permission = Object.assign({}, item);

      this.dialog_add_permission = true;
    },

    verDetalhes(item) {
      this.dialog_visualizar = true;

      this.user_logado = item;

      item.roles.forEach((role) => {
        this.user_roles.push(role.name);
      });

      item.permissions.forEach((permission) => {
        this.user_permissions.push(permission.name);
      });
    },

    deleteItemRole(item) {
      this.permission = Object.assign({}, item);
      this.dialog_eliminar_role = true;
    },

    confirmDeleteItemRole() {
      this.$inertia.delete("/permission/delete-funcao/" + this.permission.id);
      this.dialog_eliminar_role = false;
    },

    deleteItemPermission(item) {
      this.permission = Object.assign({}, item);
      this.dialog_eliminar_permission = true;
    },

    confirmDeleteItemPermission() {
      this.$inertia.delete("/permission/delete-permissao/" + this.permission.id);
      this.dialog_eliminar_permission = false;
    },

    close() {
      this.dialog = false;
      this.dialog_add_role = false;
      this.dialog_add_permission = false;
      this.role = { name: "", guard_name: "web" };
      this.permission = { name: "", guard_name: "web" };
      this.url = "";
      this.foto = "";
      setTimeout(() => {
        // this.user_logado = Object.assign({}, this.defaultItem)
        this.editedIndex = -1;
      }, 100);
    },
    save() {
      if (this.$refs["form"].validate()) {
        if (this.editedIndex > -1) {
          this.$inertia.put("/permission/editar-funcao/" + this.role.id, this.role, {
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
            },
          });
          this.close();
        } else {
          this.$inertia.post("/permission/adicionar-funcao", this.role, {
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
            },
          }),
            this.close();
        }
      }
    },

    savePermissions() {
      if (this.$refs["form"].validate()) {
        if (this.editedIndex > -1) {
          this.$inertia.put(
            "/permission/editar-permissao/" + this.permission.id,
            this.permission,
            {
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
              },
            }
          );
          this.close();
        } else {
          this.$inertia.post("/permission/adicionar-permissao", this.permission, {
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
            },
          }),
            this.close();
        }
      }
    },

    editRole() {
      this.loading = false;
      this.$inertia.visit("/permission/editar-funcao" + this.role.id, {
        method: "put",
        data: { role: this.role },
        onSuccess: () => {
          this.loading = true;
        },
      });
    },

    editPermission() {
      this.loading = true;
      this.$inertia.visit("/permission/editar-permissao" + this.permission.id, {
        method: "put",
        data: { permission: this.permission },
        onSuccess: () => {
          this.loading = false;
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
/* 
.corprincipal {
  background-color: #00897b !important;
} */

@import "vuetify/dist/vuetify.min.css";
</style>
