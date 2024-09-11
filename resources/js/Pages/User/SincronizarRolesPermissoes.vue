<template>
  <app-layout>
    <v-container>
      <v-alert v-if="$page.props.flash.success" text type="success">{{ $page.props.flash.success }}</v-alert>
      <v-alert v-if="$page.props.flash.error" text type="error">{{ $page.props.flash.error }}</v-alert>
      <v-snackbar v-if="$page.props.flash.success" v-model="bar" top :timeout="2000" color="success">
        {{ $page.props.flash.success }}
      </v-snackbar>

      <v-overlay :value="overlay">
        <v-img src="/img/spinner.gif"></v-img>
      </v-overlay>

      <section>
        <v-card>
          <v-card-title class="headline font-weight-regular corprincipal white--text">
            <v-app-bar-nav-icon color="white"></v-app-bar-nav-icon>Atribuir Permissões para cada Função
          </v-card-title>
          <v-form @submit.prevent="sincronizarPermissoes" ref="form" lazy-validation>
            <v-card-text>
              <!-- <v-row>
                <v-col cols="6"></v-col>
              </v-row> -->

              <v-toolbar flat color="white">
                <v-toolbar-title>Total({{ permissions.length }})</v-toolbar-title>
              </v-toolbar>
                <v-spacer></v-spacer>
                <v-row>
                  <v-col cols="12" sm="6" md="3">
                    <v-autocomplete v-model="role_id" append-icon="wc" name="role_id" :rules="[rules.required]"
                      item-text="name" item-value="id" :items="roles" label="Função" single-line hide-details
                      class="mx-6" @change="getRoles"></v-autocomplete>
                  </v-col>

                  <v-col cols="12" sm="6" md="3">
                    <v-text-field v-model="busca_permission" append-icon="mdi-magnify" label="Pesquisar" single-line
                      hide-details class="mx-6"></v-text-field>
                  </v-col>

                  <v-col cols="12" md="6"  class="">
                    <v-btn color="#00987b" dark type="submit" @click="validate">
                      <v-icon>sync</v-icon> Atribuir
                    </v-btn>
                    <v-btn color="#00987b" dark>
                      <inertia-link class="text-white" href="/users/user">
                        <v-icon>mdi-account-group-outline</v-icon>&nbsp;Utilizadores
                      </inertia-link>
                    </v-btn>
                  </v-col>
                </v-row>

            

              <v-data-table :headers="headers" :items="filtrerPermissoes">
                
                <template v-slot:item.actions="{ item }">
                  <div class="d-flex">
                    <v-checkbox color="#00987b" v-model="role_permissions" :value="item.name"></v-checkbox>
                  </div>
                </template>
                <template v-slot:no-data>
                  <v-btn color="#00987b" @click="initialize()">Reset</v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-form>
        </v-card>
      </section>

      <!-- <v-btn bottom color="#00987b" type="submit" dark fab fixed right @click="validate">
        <v-icon>add</v-icon>
      </v-btn> -->

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
  props: ["users", "roles", "permissions"
  ],
  components: {
    AppLayout,
  },

  data: () => ({
    bar: true,
    editedIndex: -1,
    dialog: false,
    dialog2: false,
    dialog3: false,
    role: {},
    query: { palavra_chave: '' },
    role_id: '',

    role_permissions: [],
    busca_roles: '',
    busca_permission: null,

    search_roles: "",
    search_permissions: "",
    overlay: true,
    e1: 1,
    headers: [
      {
        text: "Nº",
        align: "start",
        sortable: false,
        value: "id",
      },
      { text: "Permissão", value: "name", sortable: true },
      { text: "Estado", value: "actions", sortable: false },
    ],

    rules: {
      required: value => !!value || 'Obrigatória.',
    },

    singleSelect: false,
    headersRoles: [

      {
        text: 'Função',
        align: 'start',
        sortable: false,
        value: 'name',
      },
      { text: 'Data de Criação', value: 'created_at' },
      { text: 'Data de Modificação', value: 'updated_at' },
    ],

    headersPermissions: [
      {
        text: 'Permissão',
        align: 'start',
        sortable: false,
        value: 'name',
      },
      { text: 'Data de Criação', value: 'created_at' },
      { text: 'Data de Modificação', value: 'updated_at' },
    ],
  }),

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    formTitle() {
      return this.editedIndex === -1
        ? "Novo Utilizador"
        : "Editar Utilizador";
    },

    filtrerPermissoes() {
      if (this.busca_permission) {
        let result = this.permissions.filter((item) => {
          return item.name.toLowerCase().match(this.busca_permission) ||
            item.created_at.toLowerCase().match(this.busca_permission) ||
            item.id == this.busca_permission
        });
        return result ? result : [];
      } else {
        return this.permissions;
      }
    },
  },

  created() {
    setTimeout(() => {
      this.overlay = false;
    }, 1000);
  },

  methods: {

    validate() {
      return this.$refs.form.validate();
    },

    reset() {
      this.$refs.form.reset()
    },

    resetValidation() {
      this.$refs.form.resetValidation()
    },

    initialize() { },

    close() {
      this.$nextTick(() => {
        this.dialog = false;
        this.editedIndex = -1;
      });
    },

    sincronizarPermissoes() {
      if (this.validate()) {
        this.overlay = true;
        this.$inertia.visit('/permission/funcoes-permissoes/' + this.role_id, {
          method: 'put',
          data: { role_permissions: this.role_permissions },
          onSuccess: () => {
            setTimeout(() => {
              this.overlay = false;
              this.$inertia.reload('/permission/funcoes-permissoes/')
            }, 10);
            this.user_roles = [];
          }
        })
      }
    },

    getRoles() {
      this.overlay = false;
      axios.get('/permission/buscar-roles', { params: { role_id: this.role_id } }).then(function (response) {

        this.role = response.data;

        this.role_permissions = [];

        this.role.permissions.forEach(permission => {
          this.role_permissions.push(permission.name);
          this.overlay = false;
        });
      }.bind(this));
    },
    //fim funcoes

    getPermissions: function (page = 1) {
      this.overlay = true;
      axios.get('/permission/permissoes-usuarios?page=' + page, { params: { palavra_chave: this.busca_permission } }).then(function (response) {
        this.permissions = response.data.permissions;
        this.overlay = false;
      }.bind(this));
    },
    //fim Permissoes
  },
};
</script>

<style scoped>
.corprincipal {
  background-color: #00897B !important;
}

@import "vuetify/dist/vuetify.min.css";
</style>