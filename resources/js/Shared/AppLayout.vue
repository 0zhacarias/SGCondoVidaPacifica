<template>
    <v-app id="app" class="font-lato">
        <!--menu Vertical-->
        <v-app-bar
        height="100px"
            color="white"
            :clipped-right="$vuetify.breakpoint.lgAndUp"
            app
        >
            <v-app-bar-nav-icon
                class="text-azul"
                @click.stop="drawer = !drawer"
            >
                <v-icon>menu</v-icon>
            </v-app-bar-nav-icon>

            <!-- <v-toolbar-title class="ml-0 pl-4">
                <span class="hidden-sm-and-down">Infosat LDA</span>
            </v-toolbar-title> -->

            <!-- <v-btn text href="/ home" >
                <v-img class="align-center"  src="/img/UMA.png"
                max-width="150" ></v-img>
            </v-btn> -->
            <!--
            <v-toolbar-title class="ml-0 pl-4">
                <v-avatar>
                <v-icon  size="30">workspaces_outline</v-icon>
                </v-avatar>
                <span>GP_MUTUE</span>
            </v-toolbar-title> -->

      
            <v-spacer />
            <!-- <v-text-field
      class="hidden-sm-and-down white--text white"
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="search"
        label="Procurar"
        color="white"
      /> -->

            <v-spacer />
            <inertia-link href="/home" class="white--text remover-link">
                <v-btn icon class="white--text">
                    <v-icon>dashboard</v-icon>
                </v-btn>
            </inertia-link>

            <v-badge
                color="red "
                overlap
                :content="quantidadenotificacao"
                :value="quantidadenotificacao"
            >
                <!-- <inertia-link href="/tarefas/tarefa"> -->
                <v-menu bottom offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            icon
                            class="text-azul"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon> notifications </v-icon>
                        </v-btn>
                    </template>
                    <v-list v-if="!this.quantidadenotificacao == 0">
                        <!-- <inertia-link -->
                        <v-list-item
                            v-for="(item, i) in notificar"
                            :key="i"
                            @click="clickdialog(item)"
                        >
                            <!-- href="/notificacoes/minhas-notificacao" -->
                            <!-- class="remover-link" -->
                            <!-- <v-list-item v-for="(item, i) in notificar" :key="i" @click="() => {}"> -->
                            <!-- <v-list-item-title class="caption" dense small >{{ item.responsaveis.name }} </v-list-item-title> -->
                            <v-list-item-title class="caption" dense small
                                >{{ item.designacao }}
                            </v-list-item-title>
                            <!-- {{ notificar }} -->
                        </v-list-item>
                        <!-- </inertia-link> -->
                    </v-list>
                </v-menu>
                <v-dialog
                    v-model="dialognotificacao"
                    transition="dialog-top-transition"
                    max-width="500"
                >
                    <template v-slot:default="dialog">
                        <v-card>
                            <v-toolbar class="text-h5 pa-4" color="#0e85a3" dark
                                >Notificação ({{
                                    noti.estado_notificacao.designacao
                                }})</v-toolbar
                            >
                            <v-card-text>
                                <div class="text-h6 pa-5">
                                    {{ noti.descricao }}<br /><br />
                                </div>
                                <div class="subtitle-2 pa-4">
                                    Enviado por: {{ noti.responsaveis.name
                                    }}<br />
                                    Data envio: {{ noti.created_at }}<br />
                                </div>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn text @click="dialog.value = false"
                                    >Close</v-btn
                                >
                                <v-btn text @click="notificaca_lida()"
                                    >Visualizar</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
                <!-- </inertia-link> -->
            </v-badge>
        <span class="text-azul" color="text-azul">
                {{

                     user.name
                }}<br />
                {{
                    user.responsavel
                        ? `${user.responsavel.funcao.designacao}`
                        : "User"
                }}
            </span>
            <v-menu class="hidden-sm-and-down" left bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        text
                        v-on="on"
                        class="text-capitalize hidden-sm-and-down"
                    >
                        <v-icon>{{
                            on ? "mdi-chevron-down" : "mdi-chevron-up"
                        }}</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <inertia-link href="/users/perfils" class="remover-link">
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-icon>person</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-title>Meu Perfil</v-list-item-title>
                        </v-list-item>
                    </inertia-link>
                    <v-list-item @click="logout">
                        <v-list-item-avatar>
                            <v-icon>settings_power</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-title>Sair</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            <!-- <v-btn
                icon
                large
            >
                <v-avatar
                size="32px"
                item
                >
                <v-img
                    src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
                    alt="Vuetify"
                /></v-avatar>
            </v-btn> -->
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            app
            class="white"
            :mini-variant.sync="mini"
            expand-off-hover
            hide-overlay
        >
            <inertia-link href="/home" class="color-text-link remover-link">
                <h1 class="text-center text-bold textcor">
                    SIG-COND
                </h1>
                <div class="text-center text-sm-subtitle-2">Sistema de gestão financeira para o condominio Vida pacífica</div>
                <!-- Comentado pelo Flávio de Carvalho -->
                <!-- <v-list-item class="white" @click="dialogFoto = false"> -->
      <!--           <v-img
                    src="/assets/img/LogoM/gphoto.png"
                    class="center"
                    max-height="140"
                    max-width="140"
                    style="margin-inline: auto"
                    contain
                ></v-img> -->

                <!-- <v-list-item-avatar size="80">
                        <v-img  src="/img/person.png" v-if="user.foto==null"></v-img>
                        <v-img v-if="user.foto " src="/storage/" + user.foto ></v-img>
                    </v-list-item-avatar> -->

                <!-- Comentado pelo Flávio de Carvalho -->
                <!-- <v-list-item-title color="#0e85a3">
                        <div class="subheading"></div>
                        <div class="body-1"></div>
                    </v-list-item-title>
                </v-list-item> -->
            </inertia-link>

            <v-dialog
                transition="dialog-top-transition"
                width="600"
                hide-overlay
                persistent
                v-model="dialogFoto"
                max-width="600px"
            >
                <v-card>
                    <v-card-title class="black">
                        Atualizar Foto de Perfil
                    </v-card-title>
                    <v-card-text align="center" vuetify="center">
                        <v-avatar size="120">
                            <v-img :src="url"></v-img>
                        </v-avatar>
                        <v-file-input
                            label="File input"
                            filled
                            :rules="fotoRules"
                            prepend-icon="mdi-camera"
                            accept="image/jpeg,image/png"
                            v-model="avatar"
                            @change="onFotoChange"
                            title="Clique para adicionar a sua foto"
                        ></v-file-input>
                    </v-card-text>

                    <v-col cols="12" align-self="center">
                        <div v-if="loading" class="text-center">
                            <v-progress-circular
                                indeterminate
                                color="green"
                            ></v-progress-circular>
                        </div>
                        <v-alert
                            dense
                            text
                            v-if="alert.type == 'success'"
                            type="success"
                        >
                            {{ alert.text }}
                        </v-alert>
                        <v-alert
                            dense
                            outlined
                            v-if="alert.type == 'error'"
                            type="error"
                        >
                            {{ alert.text }}
                        </v-alert>
                    </v-col>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="dialogFoto = false">
                            <v-icon>cancel</v-icon> Cancelar
                        </v-btn>

                        <v-btn
                            color="primary"
                            type="submit"
                            @click="actualizarFoto"
                        >
                            <v-icon>save</v-icon> Salvar
                        </v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-list dense>
                <inertia-link
                    href="/home"
                    class="color-text-link remover-link py-2"
                    as="v-list-item"
                    :class="{ active: $page.component === 'Hm/Dashboard' }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.component === 'Hm/Dashboard'
                                    ? 'white'
                                    : ''
                            "
                            >mdi mdi-view-dashboard</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.component === 'Hm/Dashboard',
                        }"
                    >
                    Dashboard
                    </v-list-item-title>
                </inertia-link>
                
                <inertia-link
                    link
                    href="/blocos/bloco"
                    class="color-text-link remover-link py-2"
                    as="v-list-item"
                    v-if="user.can['gerir responsavel']"
                    :class="{
                        active: $page.url === '/blocos/bloco',
                    }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.url === '/blocos/bloco'
                                    ? 'white'
                                    : ''
                            "
                            >mdi mdi-home-city</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.url === '/blocos/bloco',
                        }"
                        >Blocos</v-list-item-title
                    >
                </inertia-link>
                  <inertia-link
                    link
                    href="/apartamentos/apartamento"
                    class="color-text-link remover-link py-2"
                    as="v-list-item"
                    v-if="user.can['gerir responsavel']"
                    :class="{
                        active: $page.url === '/apartamentos/apartamento',
                    }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.url === '/apartamentos/apartamento'
                                    ? 'white'
                                    : ''
                            "
                            >mdi mdi-home-modern</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.url === '/apartamentos/apartamento',
                        }"
                        >Apartamento</v-list-item-title
                    >
                </inertia-link>
                <inertia-link
                    link
                    href="/users/user"
                    class="color-text-link remover-link"
                    as="v-list-item"
                    v-if="user.can['gerir utilizador']"
                    :class="{
                        active: $page.url === '/users/user',
                    }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="$page.url === '/users/user' ? 'white' : ''"
                            >mdi-account-outline</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group: $page.url === '/users/user',
                        }"
                        >Utilizadores</v-list-item-title
                    >
                </inertia-link>
                
             <!--    <inertia-link
                    href="/users/perfils"
                    class="remover-link py-2 color-text-link"
                    as="v-list-item"
                    :class="{ active: $page.component === 'User/Perfil' }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.component === 'User/Perfil' ? 'white' : ''
                            "
                            >mdi mdi-account</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.component === 'User/Perfil',
                        }"
                        >Meu Perfil</v-list-item-title
                    >
                </inertia-link> -->
                <!-- <inertia-link
                href="/projectos/projecto"
                class="color-text-link remover-link py-2"
                link
                v-if="user.can['gerir projeto']"
                >
                <v-list-item>
                    <v-list-item-icon>
                    <v-icon>folder</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title> PROJECTOS </v-list-item-title>
                </v-list-item>
                </inertia-link> -->

           <!--      <v-list-group
                    prepend-icon="mdi mdi-playlist-minus"
                    class="py-2"
                    :class="{
                        active: $page.url.startsWith('/projectos'),
                    }"
                    v-if="
                        user.can['Gerir projeto'] ||
                        user.can['Gerir meus projectos']
                    "
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                title="Projectos"
                                class="font-weight-bold color-text-link"
                                :class="{
                                    active_name_group:
                                        $page.url.startsWith('/projectos'),
                                }"
                                >Despesas</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>
               
                    <inertia-link
                        href="/projectos/index-projectos"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/projectos/index-projectos',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/projectos/index-projectos'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Departamentos
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        v-if="user.can['Gerir projeto']"
                        href="/projectos/projecto"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/projectos/projecto',
                        }"
                    >
                        <v-list-item-icon>
                            <v-icon
                                :color="
                                    $page.url === '/projectos/projecto'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Projectos</v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>
                    <inertia-link
                        v-if="user.can['Gerir tipos de projeto']"
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/projectos/tipo-projecto"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/projectos/tipo-projecto',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/projectos/tipo-projecto'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Tipos de Projectos
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>
                  
                </v-list-group>
                -->

                    <inertia-link
                        v-if="user.can['Gerir tarefas gerais']"
                        href="/financas/despesa"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        as="v-list-item"
                        :class="{
                            active_name_group: $page.url === '/financas/despesa',
                            active: $page.url === '/financas/despesa',
                        }"
                        link
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/financas/despesa'
                                        ? 'white'
                                        : ''
                                "
                                >di mdi-cash-minus</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                              Despesas
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>
                    <inertia-link
                    link
                    href="/financas/factura_pagamentos"
                    class="color-text-link remover-link py-2"
                    as="v-list-item"
                    v-if="user.can['gerir responsavel']"
                    :class="{
                        active: $page.url === '/financas/factura_pagamentos',
                    }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.url === '/financas/factura_pagamentos'
                                    ? 'white'
                                    : ''
                            "
                            >mdi mdi-cash-register</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.url === '/financas/factura_pagamentos',
                        }"
                        >Facturas</v-list-item-title
                    >
                </inertia-link>
                    <inertia-link
                        v-if="user.can['Gerir tarefas gerais']"
                        href="/financas/pagamentos"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        as="v-list-item"
                        :class="{
                            active_name_group: $page.url === '/financas/pagamentos',
                            active: $page.url === '/financas/pagamentos',
                        }"
                        link
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/financas/pagamentos'
                                        ? 'white'
                                        : ''
                                "
                                >mdi mdi-account-cash</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                              Pagamentos
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        v-if="user.can['Gerir tarefas gerais']"
                        href="/financas/index"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        as="v-list-item"
                        :class="{
                            active_name_group: $page.url === '/financas/index',
                            active: $page.url === '/financas/index',
                        }"
                        link
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/financas/index'
                                        ? 'white'
                                        : ''
                                "
                                >mdi mdi-file-chart</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                              Relatório
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>
             

                <!-- <v-list-item href="/tarefas/tarefa" class="color-text-link remover-link py-2" link  v-if="user.can['gerir tarefas gerais']">
                <v-list-item-icon>
                    <v-icon>workspaces_outline</v-icon>
                </v-list-item-icon>
                <v-list-item-title>TAREFA</v-list-item-title>
                </v-list-item> -->


              <!--   <v-list-group
                    prepend-icon="circle_notifications"
                    class="py-2"
                    v-if="
                        user.can['Gerir notificações'] ||
                        user.can['Gerir Minhas Notificações']
                    "
                    :class="{
                        active: $page.url.startsWith('/notificacoes'),
                    }"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                title="Notificações"
                                class="font-weight-bold color-text-link"
                                :class="{
                                    active_name_group:
                                        $page.url.startsWith('/notificacoes'),
                                }"
                            >
                                Notificações</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <inertia-link
                        v-if="user.can['Gerir Minhas Notificações']"
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/notificacoes/minhas-notificacao"
                        link
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url ===
                                '/notificacoes/minhas-notificacao',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/notificacoes/minhas-notificacao'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Minhas Notificações
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                </v-list-group>
             

                <v-list-group
                    prepend-icon="trending_up"
                    class="py-2"
                    v-if="
                        user.can['Gerir estatisticas membros'] ||
                        user.can['Gerir estatistica projetos']
                    "
                    :class="{
                        active: $page.url.startsWith('/estatistica'),
                    }"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                title="Estatísticas"
                                class="font-weight-bold color-text-link"
                                :class="{
                                    active_name_group:
                                        $page.url.startsWith('/estatistica'),
                                }"
                                >Estatísticas</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <inertia-link
                        v-if="user.can['Gerir estatistica projetos']"
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/estatistica/projectos"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/estatistica/projectos',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/estatistica/projectos'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <span></span>

                        <v-list-item-content>
                            <v-list-item-title> Projectos</v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                </v-list-group>
                <v-list-group
                    prepend-icon="mdi mdi-table-settings"
                    class="py-2"
                    :class="{
                        active: $page.url.startsWith('/tabela_de_apoio'),
                    }"
                    v-if="user.can['Gerir apoio']"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                title="Utilizadores"
                                class="font-weight-bold color-text-link"
                                :class="{
                                    active_name_group:
                                        $page.url.startsWith(
                                            '/tabela_de_apoio'
                                        ),
                                }"
                                >Tabela de Apoio</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <inertia-link
                        v-if="user.can['Gerir funções']"
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        href="/tabela_de_apoio/funcoes"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/tabela_de_apoio/funcoes',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/tabela_de_apoio/funcoes'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title> Funções </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        href="/tabela_de_apoio/estado_tarefa"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/tabela_de_apoio/estado_tarefa',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/tabela_de_apoio/estado_tarefa'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Estados da tarefa</v-list-item-title
                            >
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/tabela_de_apoio/estado_projeto"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/tabela_de_apoio/estado_projeto',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/tabela_de_apoio/estado_projeto'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title
                                >Estados do Projeto
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/tabela_de_apoio/estado_civil"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/tabela_de_apoio/estado_civil',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/tabela_de_apoio/estado_civil'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Estado Civil </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/tabela_de_apoio/tecnologias"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/tabela_de_apoio/tecnologias',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url === '/tabela_de_apoio/tecnologias'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            ></v-list-item-action
                        >
                        <v-list-item-content>
                            <v-list-item-title>Tecnologias</v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link font-weight-medium text-caption"
                        href="/tabela_de_apoio/tipo-tecnologias"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url ===
                                '/tabela_de_apoio/tipo-tecnologias',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/tabela_de_apoio/tipo-tecnologias'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Tipos de Tecnologias
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>
                </v-list-group> 

                <v-list-group
                    prepend-icon="wrap_text"
                    class="py-2"
                    v-if="user.can['Gerir Parametrização']"
                    :class="{
                        active: $page.url.startsWith('/modulos'),
                    }"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                title="Parametrização"
                                class="font-weight-bold color-text-link"
                                :class="{
                                    active_name_group:
                                        $page.url.startsWith('/modulos'),
                                }"
                                >Parametrização</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <inertia-link
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        href="/modulos/action-modulo-parametrizado"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url ===
                                '/modulos/action-modulo-parametrizado',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/modulos/action-modulo-parametrizado'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                Parametrização Acção
                            </v-list-item-title>
                        </v-list-item-content>
                    </inertia-link>

                    <inertia-link
                        class="color-text-link remover-link py-2 font-weight-medium text-caption"
                        href="/modulos/modulo-parametrizado"
                        as="v-list-item"
                        :class="{
                            active_name_group:
                                $page.url === '/modulos/modulo-parametrizado',
                        }"
                    >
                        <v-list-item-action>
                            <v-icon
                                :color="
                                    $page.url ===
                                    '/modulos/modulo-parametrizado'
                                        ? 'white'
                                        : ''
                                "
                                >list</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title
                                >Parametrização Módulo</v-list-item-title
                            >
                        </v-list-item-content>
                    </inertia-link>
                </v-list-group>
                <inertia-link
                    v-if="user.can['gerir permissao']"
                    link
                    class="color-text-link remover-link"
                    href="/permission/funcoes-permissoes"
                    as="v-list-item"
                    :class="{
                        active: $page.url === '/permission/funcoes-permissoes',
                    }"
                >
                    <v-list-item-icon>
                        <v-icon
                            :color="
                                $page.url === '/permission/funcoes-permissoes'
                                    ? 'white'
                                    : ''
                            "
                            >settings</v-icon
                        >
                    </v-list-item-icon>
                    <v-list-item-title
                        class="font-weight-bold"
                        :class="{
                            active_name_group:
                                $page.url === '/permission/funcoes-permissoes',
                        }"
                        >Funções e Permissões</v-list-item-title
                    >
                </inertia-link>
                -->
              
                <v-list-item link @click="logout" class="py-1">
                    <v-list-item-icon>
                        <v-icon>mdi-account-arrow-left</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title
                            class="font-weight-bold color-text-link"
                            >Sair</v-list-item-title
                        >
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Sizes your content based upon application components -->
        <v-main id="#scrolling-techniques-1" class="body">
            <!-- Provides the application the proper gutter -->
            <v-container>
                <!-- <v-overlay :value="overlay">
          <v-img src="/img/loader4.gif"></v-img>
        </v-overlay> -->
                <slot />
            </v-container>
        </v-main>


    </v-app>
</template>

<script>
import Vue from "vue";
import VuetifyMask from "vuetify-mask";
const gradients = [
    ["#222"],
    ["#42b3f4"],
    ["red", "orange", "yellow"],
    ["purple", "violet"],
    ["#00c6ff", "#F0F", "#FF0"],
    ["#f72047", "#ffd200", "#1feaea"],
];
export default {
    props: ["msg", "responsavel"],
    data: () => ({
        notificar: "",
        noti: "",
        dialognotificacao: false,
        quantidadenotificacao: 0,
        totalNotificacoes: 0,
        notificar: [
            { title: "designacao" },
            { title: "Minha Conta", icon: "mdi-account" },
            { title: "Minha " },
        ],
        dialogFoto: false,
        loading: false,
        alert: { text: "", type: "" },
        avatar: [],
        notificar_id: {},
        url: "/img/default-avatar.png",
        app: false,
        show: false,
        overlay: true,
        offsetTop: 0,

        fotoRules: [(v) => !!v || "Deve Carregar a Foto"],

        path: `${window.location.origin}/storage/utilizador/profiles`, //caminho base
        picker: "",
        drawer: true,

        mini: false,
        width: 2,
        radius: 10,
        padding: 8,
        lineCap: "round",
        gradient: gradients[5],
        value: [0, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
        gradientDirection: "top",
        gradients,
        fill: false,
        autoLineWidth: false,
        query: { id: 0 },
        user_roles: [],
        roles: {},

        notificationsItem: [],
        notificationsItem2: {
            data: {
                dados_notificacao: {
                    notificacao: {
                        tipo_evento_notificacao: {},
                    },
                },
            },
        },
    }),

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        notifications() {
            if (this.notificationsItem != []) {
                return this.notificationsItem;
            } else {
                return this.notificationsItem2;
            }
        },
    },

    mounted() {},

    created() {
        // this.atualizarTarefasAtrasada();
        // this.notificarTarefasAtrasada();
        this.Notificar();
    },

    methods: {
        atualizarTarefasAtrasada() {
            axios
                .get("/atualizar-tarefas-atrasada")
                .then((response) => {
                    this.tarefaatrasada = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        // Metodo Para notificar atraso com base em um parametro de ação
        notificarTarefasAtrasada() {
            axios
                .get("/notificar-tarefas-atrasada")
                .then((response) => {
                    this.notificartarefaatrasada = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        Notificar() {
            axios
                .get("/visualizar-notificar")
                .then((response) => {
                    // alert(this.quantidadenotificacao)
                    this.notificar = response.data.notificacoes;
                    this.quantidadenotificacao =
                        response.data.quantidade_notificacao;
                    // this.quantidadenotificacao = this.qzerar
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        // lida(){
        //   this.quantidadenotificacao=0
        //   this.$inertia.post(
        //     "/notificacoes/notificaca-lida")
        // },
        clickdialog(item) {
            this.dialognotificacao = true;
            this.noti = Object.assign({}, item);
            // alert(JSON.stringify(this.noti))
        },

        notificaca_lida() {
            this.notificar_id.id_notificar=this.noti.id
           /*  this.$inertia.put(
                `/notificacoes/notificaca-lida/${this.noti.id}`,
                this.noti
            ); */
            this.$inertia.get(
                `/notificacoes/notificaca-lida/${btoa(btoa(btoa(this.noti.id)))}`

            );
            this.Notificar();
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

        logout() {
            axios.post("/logout").then((response) => {
                window.location.reload();
            });
        },

        onScroll(e) {
            this.offsetTop = e.target.scrollTop;
        },

        onFotoChange(e) {
            if (this.avatar) {
                this.url = URL.createObjectURL(this.avatar);
            } else {
                this.url = "/img/default-avatar.png";
            }
        },

        validate() {
            return this.$refs.form.validate();
        },

        actualizarFoto: function () {
            this.loading = true;

            const config = {
                headers: { "content-type": "multipart/form-data" },
            };
            let formData = new FormData();
            formData.append("user_id", this.user.id);
            formData.append("foto", this.avatar);
            axios
                .post("/user/actualizar-foto-de-perfil", formData, config)
                .then((response) => {
                    this.alert.text = response.data;
                    this.alert.type = "success";

                    this.loading = false;
                    window.location.reload();
                })
                .catch((error) => {
                    this.alert.text = error.response.data.errors;
                    this.alert.type = "error";
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,400;1,700&display=swap");
.body {
    font-family: "Lato", sans-serif;
    background: rgb(1, 65, 95);
    background: linear-gradient(
        250deg,
        rgba(131, 58, 180, 0.04525560224089631) 0%,
        rgba(253, 29, 29, 0.053658963585434205) 40%,
        rgba(101, 235, 88, 0.04245448179271705) 71%
    );
}
.corprincipal {
    background-color: #0e85a3 !important;
}
.textcor{
    color: #0e85a3;
    font-weight: 800;
    margin-top: .1rem;
    margin-bottom: 0;

}
.text-azul{
    color: #0e85a3;
}

.active {
    color: #ffffff;
    background: #0e85a3;
}

.active_name_group {
    color: #ffffff !important;
}

.font-lato {
    font-family: "Lato";
}

.color-text-link:hover {
    color: #0e85a3 !important;
}

.color-text-link:a hover {
    color: #0e85a3 !important;
}

.remover-link {
    text-decoration: none !important;

}

.remover-link:visited {
    color: black;
    
}

.v-application .primary--text {
    color: black !important;
    font-weight: bold;

    font-size: 0.8125rem;
    font-weight: 500;
    line-height: 1rem;
}

.nome {
    width: 12em;
    height: em;
}

.nome_usuario {
    width: 12em;
    height: 1.5em;
    float: left;
}
</style>
