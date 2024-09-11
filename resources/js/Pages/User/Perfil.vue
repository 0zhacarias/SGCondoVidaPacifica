<template>
    <app-layout>
        <div class="team bg">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="3" class="mb-11">
                        <v-card class="mx-auto">
                            <v-divider></v-divider>

                            <v-img
                                style="border-radius: 50%"
                                height="200"
                                width="200"
                                class="mx-auto"
                                @click="Trocar()"
                                elevation-2
                                src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
                            ></v-img>

                            <v-card-title class="justify-center">{{
                                usuario.name
                            }}</v-card-title>

                            <v-card-text>
                                <v-row align="center" class="mx-0">
                                    <button type="button">
                                        Dados Pessoais
                                    </button>
                                </v-row>
                            </v-card-text>

                            <v-divider class="mx-4"></v-divider>
                            <v-card-text> </v-card-text>

                            <v-card-actions class="mr-auto">
                                <v-btn class="my-1" block outlined rounded
                                    >Daos pessoais
                                </v-btn>
                            </v-card-actions>
                            <v-card-actions>
                                <v-btn block outlined rounded
                                    >Documentos
                                </v-btn>
                            </v-card-actions>
                            <v-card-actions>
                                <v-btn block outlined rounded
                                    >Pagamentos
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="9">
                        <v-card flat class="pb-4">
                            <v-card-title class="text-h6">
                                Dados pessoais
                                <!--    {{
                                                usuario.responsavel
                                                    ? `${usuario.responsavel.nome_responsavel} ${usuario.responsavel.sobre_nome_responsavel} `
                                                    : usuario.name
                                            }} -->
                                <v-btn
                                    class="mx-2 text-right"
                                    fab
                                    small
                                    :href="
                                        '/users/arquivo-pdf-perfil/' +
                                        usuario.id
                                    "
                                    target="__blank"
                                    title="Exportar os dados para pdf"
                                >
                                    <v-icon> mdi mdi-printer </v-icon>
                                </v-btn>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-title class="text-h6">
                                Identificação
                            </v-card-title>
                            <v-row class="mx-auto" v-if="!usuario.responsavel">
                                <v-col cols="12" md="6">
                                    <div class="font-weight-normal">
                                        <strong> Género:</strong>
                                        {{ usuario.email }}
                                    </div>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <div class="font-weight-normal">
                                        <strong> Telefone :</strong>
                                        {{ usuario.telefone }}
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row class="mx-auto" v-if="usuario.responsavel">
                                <v-col cols="12" md="6">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel.genero"
                                    >
                                        <strong> Genéro :</strong>
                                        {{
                                            usuario.responsavel.genero
                                                .designacao
                                        }}
                                    </div>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel"
                                    >
                                        <strong> Estado Cívil :</strong>
                                        {{
                                            usuario.responsavel.estado_civil
                                                .designacao
                                        }}
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row class="mx-auto">
                                <v-col cols="12" md="6">
                                    <div class="font-weight-normal">
                                        <strong>Tipo Documento: </strong>
                                        Bilhete de Identidade
                                    </div>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel"
                                    >
                                        <strong> Nº do Documento :</strong>
                                        {{
                                            usuario.responsavel
                                                .numero_identificacao
                                        }}
                                    </div>
                                </v-col>
                            </v-row>
                            <v-divider></v-divider>
                            <v-card-title class="text-h6">
                                Outras Informações
                            </v-card-title>
                            <v-row class="mx-auto">
                                <v-col cols="12" md="4">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel"
                                    >
                                        <strong> Email:</strong>
                                        {{
                                            usuario.responsavel
                                                .email_responsavel
                                        }}
                                    </div>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel"
                                    >
                                        <strong> Telefone :</strong>
                                        {{
                                            usuario.responsavel
                                                .telefone_responsavel
                                        }}
                                    </div>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <div
                                        class="font-weight-normal"
                                        v-if="usuario.responsavel"
                                    >
                                        <strong> Função:</strong>
                                        {{
                                            usuario.responsavel.funcao
                                                .designacao
                                        }}
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card>

                        <v-card class="my-2" flat>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    dark
                                    bottom
                                    color="corprincipal"
                                    @click="editedItem(item)"
                                    right
                                >
                                    <v-icon title="Editar os dados"
                                        >mdi-account-edit</v-icon
                                    >
                                    editar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
         <!--    <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="4" class="mb-11">
                        <v-card class="mx-auto">
                            <v-divider></v-divider>

                            <v-img
                                style="border-radius: 50%"
                                height="200"
                                width="200"
                                class="mx-auto"
                                @click="Trocar()"
                                elevation-2
                                src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
                            ></v-img>

                            <v-card-title class="justify-center">{{
                                usuario.name
                            }}</v-card-title>

                            <v-card-text>
                                <v-row align="center" class="mx-0">
                                    <button type="button">
                                        Dados Pessoais
                                    </button>
                                </v-row>
                            </v-card-text>

                            <v-divider class="mx-4"></v-divider>

                            <v-card-title>Tonight's availability</v-card-title>

                            <v-card-text> </v-card-text>

                            <v-card-actions>
                                <v-btn
                                    color="deep-purple lighten-2"
                                    text
                                    @click="
                                    "
                                >
                                    Reserve
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="8">
                        <v-card>
                            <v-toolbar dark flat prominent>
                                <v-card-text class="text-h3"
                                    >Minha Conta</v-card-text
                                >

                                <template v-slot:extension>
                                    <v-tabs v-model="tabs" centered>
                                        <v-tab> Informações da Conta </v-tab>
                                        <v-tab> Informações Técnicas </v-tab>
                                        <v-tab> Alterar senha </v-tab>
                                    </v-tabs>
                                </template>
                            </v-toolbar>

                            <v-tabs-items v-model="tabs">
                                <v-tab-item>
                                    <v-card flat class="pb-4">
                                        <v-card-title class="text-h6">
                                            Utilizador:
                                            {{
                                                usuario.responsavel
                                                    ? `${usuario.responsavel.nome_responsavel} ${usuario.responsavel.sobre_nome_responsavel} `
                                                    : usuario.name
                                            }}
                                            <v-btn
                                                class="mx-2 text-right"
                                                fab
                                                small
                                                :href="
                                                    '/users/arquivo-pdf-perfil/' +
                                                    usuario.id
                                                "
                                                target="__blank"
                                                title="Exportar os dados para pdf"
                                            >
                                                <v-icon>
                                                    mdi mdi-printer
                                                </v-icon>
                                            </v-btn>
                                        </v-card-title>
                                        <v-btn
                                            v-model="fab"
                                            dark
                                            fab
                                            bottom
                                            color="corprincipal"
                                            @click="editedItem(item)"
                                            right
                                        >
                                            <v-icon title="Editar os dados"
                                                >mdi-account-edit</v-icon
                                            >
                                        </v-btn>
                                        <v-divider></v-divider>
                                        <v-card-title class="text-h6">
                                            Identificação
                                        </v-card-title>
                                        <v-row
                                            class="mx-auto"
                                            v-if="!usuario.responsavel"
                                        >
                                            <v-col cols="12" md="6">
                                                <div class="font-weight-normal">
                                                    <strong> Género:</strong>
                                                    {{ usuario.email }}
                                                </div>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <div class="font-weight-normal">
                                                    <strong> Telefone :</strong>
                                                    {{ usuario.telefone }}
                                                </div>
                                            </v-col>
                                        </v-row>
                                        <v-row
                                            class="mx-auto"
                                            v-if="usuario.responsavel"
                                        >
                                            <v-col cols="12" md="6">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="
                                                        usuario.responsavel
                                                            .genero
                                                    "
                                                >
                                                    <strong> Genéro :</strong>
                                                    {{
                                                        usuario.responsavel
                                                            .genero.designacao
                                                    }}
                                                </div>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="usuario.responsavel"
                                                >
                                                    <strong>
                                                        Estado Cívil :</strong
                                                    >
                                                    {{
                                                        usuario.responsavel
                                                            .estado_civil
                                                            .designacao
                                                    }}
                                                </div>
                                            </v-col>
                                        </v-row>
                                        <v-row class="mx-auto">
                                            <v-col cols="12" md="6">
                                                <div class="font-weight-normal">
                                                    <strong
                                                        >Tipo Documento:
                                                    </strong>
                                                    Bilhete de Identidade
                                                </div>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="usuario.responsavel"
                                                >
                                                    <strong>
                                                        Nº do Documento
                                                        :</strong
                                                    >
                                                    {{
                                                        usuario.responsavel
                                                            .numero_identificacao
                                                    }}
                                                </div>
                                            </v-col>
                                        </v-row>
                                        <v-divider></v-divider>
                                        <v-card-title class="text-h6">
                                            Outras Informações
                                        </v-card-title>
                                        <v-row class="mx-auto">
                                            <v-col cols="12" md="4">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="usuario.responsavel"
                                                >
                                                    <strong> Email:</strong>
                                                    {{
                                                        usuario.responsavel
                                                            .email_responsavel
                                                    }}
                                                </div>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="usuario.responsavel"
                                                >
                                                    <strong> Telefone :</strong>
                                                    {{
                                                        usuario.responsavel
                                                            .telefone_responsavel
                                                    }}
                                                </div>
                                            </v-col>

                                            <v-col cols="12" md="4">
                                                <div
                                                    class="font-weight-normal"
                                                    v-if="usuario.responsavel"
                                                >
                                                    <strong> Função:</strong>
                                                    {{
                                                        usuario.responsavel
                                                            .funcao.designacao
                                                    }}
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="text-h5">
                                            Sobre Mim
                                        </v-card-title>
                                        <v-btn
                                            v-model="fab"
                                            dark
                                            fab
                                            bottom
                                            color="corprincipal"
                                            @click="viewSkills()"
                                            v-if="editarSkills"
                                            right
                                        >
                                            <v-icon title="Visualizar os dados"
                                                >mdi
                                                mdi-account-settings</v-icon
                                            >
                                        </v-btn>
                                        <v-btn
                                            v-model="fab"
                                            dark
                                            fab
                                            bottom
                                            color="corprincipal"
                                            @click="editedSkills()"
                                            v-else
                                            right
                                        >
                                            <v-icon title="Editar os dados"
                                                >mdi
                                                mdi-account-question</v-icon
                                            >
                                        </v-btn>
                                        <v-row
                                            v-if="!editarSkills"
                                            class="pt-4"
                                        >
                                            <v-col>
                                                <v-card
                                                    class="ma-2"
                                                    elevation="0"
                                                >
                                                    <v-card-title
                                                        >Habilidades</v-card-title
                                                    >
                                                    <v-row>
                                                        <v-col cols="2"
                                                            ><v-card-text
                                                                >Tecnologia:
                                                                {{
                                                                    tecnologiasDisponiveis[2]
                                                                }}<br />
                                                                Nivel:
                                                                {{ niveis[2] }}
                                                                <br />
                                                                Ano de
                                                                experencia:
                                                                {{ experencia }}
                                                                <br /></v-card-text
                                                        ></v-col>
                                                        <v-col cols="2"
                                                            ><v-card-text
                                                                >Tecnologia:
                                                                {{
                                                                    tecnologiasDisponiveis[4]
                                                                }}<br />
                                                                Nivel:
                                                                {{ niveis[1] }}
                                                                <br />
                                                                Ano de
                                                                experencia:
                                                                {{ experencia }}
                                                                <br /></v-card-text
                                                        ></v-col>
                                                    </v-row>

                                                    <v-divider></v-divider>
                                                    <v-card-text class="py-0">
                                                        <span
                                                            class="font-weight-bold"
                                                            >Experiencia
                                                            detalhada:</span
                                                        >
                                                        {{ outrosDetalhes }}
                                                    </v-card-text>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                        <v-row class="pt-6" v-else>
                                            <v-form
                                                ref="formprojecto"
                                                lazy-validation
                                            >
                                                <v-card-text>
                                                    <v-row dense>
                                                        <v-row
                                                            cols="12"
                                                            v-for="(
                                                                modulo_projecto,
                                                                index
                                                            ) in fields"
                                                            :key="index"
                                                        >
                                                            <v-col
                                                                cols="12"
                                                                md="4"
                                                                class="pb-0"
                                                            >
                                                                <v-autocomplete
                                                                    v-model="
                                                                        modulo_projecto.tecnologias
                                                                    "
                                                                    dense
                                                                    :items="
                                                                        tecnologiasDisponiveis
                                                                    "
                                                                    label="Tecnologias"
                                                                    outlined
                                                                ></v-autocomplete>
                                                            </v-col>

                                                            <v-col
                                                                cols="12"
                                                                md="4"
                                                                class="pb-0"
                                                            >
                                                                <v-select
                                                                    dense
                                                                    v-model="
                                                                        modulo_projecto.nivel
                                                                    "
                                                                    :items="
                                                                        niveis
                                                                    "
                                                                    label="Nível"
                                                                    outlined
                                                                ></v-select>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                md="4"
                                                                class="pb-0"
                                                            >
                                                                <v-text-field
                                                                    dense
                                                                    outlined
                                                                    label="Anos de Experiências"
                                                                    v-model="
                                                                        modulo_projecto.experencia
                                                                    "
                                                                    required
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="pa-0">
                                                                <v-btn
                                                                    v-if="
                                                                        index !==
                                                                        0
                                                                    "
                                                                    class="ml-4"
                                                                    @click="
                                                                        removeField(
                                                                            index
                                                                        )
                                                                    "
                                                                >
                                                                    Remover
                                                                </v-btn>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row class="mt-0">
                                                            <v-col
                                                                cols="12"
                                                                md="12"
                                                                class="pb-0"
                                                            >
                                                                <v-checkbox
                                                                    v-model="
                                                                        checkbox1
                                                                    "
                                                                    label="Outros"
                                                                ></v-checkbox>
                                                            </v-col>
                                                            <v-divider></v-divider>
                                                            <v-col
                                                                cols="12"
                                                                md="12"
                                                                v-if="checkbox1"
                                                            >
                                                                <v-textarea
                                                                    v-model="
                                                                        fields.descricao
                                                                    "
                                                                    label="Outras informações"
                                                                    outlined
                                                                ></v-textarea>
                                                            </v-col>
                                                        </v-row>
                                                    </v-row>
                                                </v-card-text>
                                                <div class="mx-auto pb-4">
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="red darken-3"
                                                            text
                                                            @click="
                                                                cancelarDialog()
                                                            "
                                                        >
                                                            Cancelar
                                                        </v-btn>
                                                        <v-btn
                                                            text
                                                            @click="
                                                                addOthersExperiencs()
                                                            "
                                                            >Adicionar +</v-btn
                                                        >
                                                        <v-btn
                                                            color="blue darken-1"
                                                            text
                                                            @click="save()"
                                                        >
                                                            {{
                                                                editedIndex > -1
                                                                    ? "Actualizar"
                                                                    : "Cadastrar"
                                                            }}
                                                        </v-btn>
                                                    </v-card-actions>
                                                </div>
                                            </v-form>
                                        </v-row>
                                    </v-card>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="text-h5">
                                            Alterar senha
                                        </v-card-title>
                                        <v-card-text>
                                            <v-form
                                                ref="formpassword"
                                                lazy-validation
                                            >
                                                <v-row class="mx-auto">
                                                    <v-col cols="12" md="4">
                                                        <div
                                                            class="font-weight-normal"
                                                            v-if="
                                                                usuario.responsavel
                                                            "
                                                        >
                                                            <strong>
                                                                Senha
                                                                actual:</strong
                                                            >
                                                            <v-text-field
                                                                v-model="
                                                                    usuario.password_antiga
                                                                "
                                                                type="password"
                                                                label="Insere a senha atual"
                                                                prepend-icon="password"
                                                                :rules="
                                                                    passwordAntigaRules
                                                                "
                                                                :error-messages="
                                                                    erros.password
                                                                "
                                                            ></v-text-field>
                                                        </div>
                                                    </v-col>
                                                    <v-col cols="12" md="3">
                                                        <div
                                                            class="font-weight-normal"
                                                            v-if="
                                                                usuario.responsavel
                                                            "
                                                        >
                                                            <strong>
                                                                Nova
                                                                senha:</strong
                                                            >
                                                            <v-text-field
                                                                v-model="
                                                                    usuario.password
                                                                "
                                                                type="password"
                                                                label="Inserir senha"
                                                                prepend-icon="password"
                                                                :rules="
                                                                    passwordRules
                                                                "
                                                                :error-messages="
                                                                    erros.password
                                                                "
                                                            ></v-text-field>
                                                        </div>
                                                    </v-col>
                                                    <v-col cols="12" md="3">
                                                        <div
                                                            class="font-weight-normal"
                                                            v-if="
                                                                usuario.responsavel
                                                            "
                                                        >
                                                            <strong>
                                                                Confirmar
                                                                :</strong
                                                            >
                                                            <v-text-field
                                                                v-model="
                                                                    confirmar
                                                                "
                                                                type="password"
                                                                label="Inserir senha"
                                                                prepend-icon="password"
                                                                :rules="[
                                                                    passwordConfirmarRules.Valido(
                                                                        confirmar,
                                                                        usuario.password
                                                                    ),
                                                                ]"
                                                                :error-messages="
                                                                    erros.confirmar
                                                                "
                                                            ></v-text-field>
                                                        </div>
                                                    </v-col>
                                                    <v-col md="2">
                                                        <br />
                                                        <v-btn
                                                            dark
                                                            color="#1e4b32"
                                                            @click="
                                                                savePassword()
                                                            "
                                                        >
                                                            editar</v-btn
                                                        >
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs-items>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container> -->
            <v-dialog
                v-if="dialog"
                v-model="dialog"
                max-width="700px"
                persistent
            >
                <v-card>
                    <v-toolbar
                        elevation="2"
                        class="text-uppercase font-weight-bold"
                    >
                        <v-toolbar-title>Actualizar Usuário </v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-form ref="form" lazy-validation>
                        <v-container>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="usuario.name"
                                            label="Nome completo do usuario"
                                            hide-details="auto"
                                            prepend-icon="person"
                                            :rules="nomeCompletoRules"
                                            :error-messages="erros.name"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            disabled
                                            v-model="usuario.username"
                                            label="Nome de utilizador"
                                            hide-details="auto"
                                            prepend-icon="person"
                                            :rules="nomeCompletoRules"
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
                                            label="Número de telefone(*)"
                                            type="number"
                                            v-model="usuario.telefone"
                                            prepend-icon="phone_in_talk"
                                            :counter="9"
                                            :rules="telefoneRules"
                                            v-bind:focus="focus"
                                            v-on:focus="focus = false"
                                            :error-messages="erros.email"
                                            v-bind:properties="{
                                                readonly: false,
                                                disabled: false,
                                                outlined: false,
                                                clearable: false,
                                                placeholder: '',
                                            }"
                                            v-bind:options="{
                                                inputMask: '###-###-###',
                                                outputMask: '###########',
                                                empty: null,
                                                applyAfter: false,
                                            }"
                                        ></v-text-field>
                                        <!-- <v-text-field-integer
                                        v-model="usuario.telefone"
                                        v-bind:properties="{
                                            readonly: false,
                                            disabled: false,
                                            outlined: false,
                                            clearable: false,
                                            placeholder: '',
                                        }"
                                        v-bind:options="{
                                            inputMask: '###-###-###',
                                            outputMask: '###########',
                                            empty: null,
                                            applyAfter: false,
                                        }"
                                        label="Número de telefone(*)"
                                        v-bind:focus="focus"
                                        v-on:focus="focus = false"
                                        :rules="telefoneRules"
                                        :error-messages="erros.telefone"
                                        prepend-icon="phone_in_talk"
                                        :counter="9"
                                        /> -->
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn
                                    color="red"
                                    dark
                                    @click="cancelarDialog()"
                                    >Cancelar</v-btn
                                >
                                <v-btn color="#00987b" dark @click="save()">{{
                                    "Actualizar"
                                }}</v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-form>
                </v-card>
            </v-dialog>
        </div>
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
    props: [
        "responsavel",
        "funcao",
        "usuario",
        "genero",
        "estadoCivil",
        "generos",
    ],
    components: {
        AppLayout,
    },

    data() {
        return {
            desib: -1,
            // usuario: {},
            confirmar: "",
            usuario: {},
            checkbox1: false,
            defaultusuario: {},
            editedIndex: -1,
            dialog: false,
            dialogPassword: false,
            dialogDetalheUsuario: false,
            niveis: ["Iniciante", "Intermediário", "Avançado", "Especialista"],
            tecnologiasDisponiveis: [
                "Vue.js",
                "React",
                "Angular",
                "Node.js",
                "Spring Boot",
                "Django",
                "Flask",
                "Express.js",
            ],
            experencia: 12,
            outrosDetalhes:
                " Lorem ipsum dolor sit amet consectetur, adipisicing elit. Beatae nostrum, ipsum reprehenderit quasi quaerat maiores, necessitatibus maxime tempora assumenda nisi minima corrupti, eos voluptate quam pariatur iusto aspernatur soluta.",
            fields: [
                {
                    tecnologias: [],
                    experencia: null,
                    nivel: [],
                    descricao: "",
                },
            ],
            panel: [0],
            tecnologias: [],
            tabs: null,
            dialogDeleteUsuario: false,
            search: "",
            erros: [],
            allowSpaces: false,
            match: "",
            max: 0,
            editarSkills: false,
            model: "",
            // botao flutuante
            direction: "left",
            fab: false,
            fling: false,
            hover: true,
            tabs: null,
            top: false,
            right: true,
            bottom: false,
            left: false,
            transition: "slide-y-reverse-transition",
            // restricoes no front
            passwordConfirmarRules: {
                Valido(v, pass) {
                    return (v) => (!!v && v) === pass || "A senha não é igual";
                },
            },
            passwordRules: [
                (v) => !!v || "Campo Obrigatório",
                (v) =>
                    (v && v.length >= 8) ||
                    "Senha muito fraca digita no minimo 8 caracter!",
            ],
            passwordAntigaRules: [(v) => !!v || "Campo Obrigatório"],

            telefoneRules: [
                (v) => !!v || "Telefone é Obrigatório",
                (v) =>
                    (v && v.length <= 9 && v.length >= 9) ||
                    "O Numero deve ter 9 digitos no Minino",
                // (v) => (v && v.length >= 9) || "O Numero deve ter 9 digitos no Minino",
            ],
            emailRules: [
                (v) => !!v || "E-mail Obrigatório",
                (v) => /.+@.+\..+/.test(v) || "E-mail Inválida (*)",
            ],
            //
            nomeCompletoRules: [(v) => !!v || "Campo Obrigatório(*)"],
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
            this.$refs.form.validate();
        },

        removeField(index) {
            this.fields.splice(index, 1);
        },

        addOthersExperiencs() {
            this.fields.push({
                // descricao_tipo_problema: "",
                // designacao_tipo_problema: "",
            });
        },

        editedItemPassword() {
            this.dialogPassword = true;
        },

        cancelarDialogPassword() {
            this.dialogPassword = false;
        },

        //Botao alterar minha senha

        editedItem() {
            this.dialog = true;
        },

        editedSkills() {
            this.editarSkills = true;
        },

        viewSkills() {
            this.editarSkills = false;
        },

        cancelarDialog() {
            this.dialog = false;
        },
        closeSave() {
            // this.usuario = Object.assign({}, this.defaultusuario);
            // this.editedIndex = -1;
            this.erros = [];
            this.dialog = false;
        },
        save() {
            if (this.$refs["form"].validate()) {
                this.$inertia.put(
                    "/users/atualizar-perfil/" + this.usuario.id,
                    this.usuario,
                    {
                        onFinish: () => {
                            Vue.toasted.global.defaultSuccess({
                                msg: "" + this.$page.props.flash.success,
                            });
                            this.closeSave();
                        },
                    }
                );
            }
        },

        savePassword() {
            if (this.$refs["formpassword"].validate()) {
                this.$inertia.put(
                    "/users/atualizar-senha/" + this.usuario.id,
                    this.usuario,
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
                // this.erros = [];
            }
        },
    },
};
</script>

<style>
table {
    font-weight: bold;
}
.v-speed-dial {
    position: absolute;
}

.v-btn--floating {
    position: relative;
}

.corprincipal {
    background-color: #1e4b32 !important;
}

@import "vuetify/dist/vuetify.min.css";
</style>
