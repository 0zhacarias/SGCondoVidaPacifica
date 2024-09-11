<template>
    <app-layout>
        <v-container>
            <v-card elevation="0">
                <v-row>
                    <v-col cols="6" sm="6" md="6">
                        <h3 class="font-weight-bold">
                            Blocos ({{ this.blocos.length }})
                        </h3>
                    </v-col>

                    <v-col class="text-right">
                        <v-btn
                            color="corprincipal"
                            title="Adicionar novo projecto"
                            class="white--text font-weight-bold"
                            @click="carregarDialog()"
                            >Adicionar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>

            <v-card class="my-5 elevation-0">
                <div
                    class="text-h5 mx-2 font-weight-bold"
                    style="color: #0a6e84"
                >
                    Filtros <span class="mdi mdi-filter-outline"></span>
                </div>
                <v-card-title>
                    <v-row class="mx-2 mt-5">
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Bloco</label>
                            <!-- <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Pesquisar projectos"
                dense

              >
              </v-text-field> -->
                            <v-autocomplete
                                prepend-icon=""
                                @change="filtrarEstado()"
                                v-model="query.projecto_id"
                                :items="filtroProjectos"
                                item-value="id"
                                item-text="nome_proj"
                                type="text"
                                outlined
                                clearable
                                dense
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for="">Sindicos</label>
                            <v-autocomplete
                                prepend-icon=""
                                @change="filtrarEstado()"
                                v-model="query.responsavel_id"
                                :items="gestores"
                                item-value="id"
                                item-text="nome_pessoa"
                                type="text"
                                outlined
                                clearable
                                dense
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Nº apartamento</label>
                            <v-text-field
                                @change="filtrarEstado()"
                                clearable
                                v-model="query.estado_bloco_id"
                                :items="estadoblocos"
                                item-text="designacao"
                                item-value="id"
                                prepend-icon=""
                                outlined
                                dense
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6" sm="6" md="2">
                            <label for="">Tipologia</label>
                            <v-autocomplete
                                @change="filtrarEstado()"
                                clearable
                                v-model="query.estado_bloco_id"
                                :items="estadoblocos"
                                item-text="designacao"
                                item-value="id"
                                prepend-icon=""
                                label="Tipologia"
                                outlined
                                dense
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="6" sm="6" md="3">
                            <label for=""></label>
                            <v-text-field
                                @input="filtrarData()"
                                label="Data de Criação"
                                v-model="query.data_inicial"
                                type="date"
                                :rules="dataInicialfiltroRules"
                                :error-messages="erros.dat_inicio_real"
                                outlined
                                dense
                            >
                            </v-text-field>
                        </v-col>
                        <!--           <v-col cols="6" sm="6" md="2">
                            <label for=""></label>
                            <v-text-field
                                label="Data termino"
                                v-model="query.data_final"
                                @input="filtrarData()"
                                clearable
                                type="date"
                                :disabled="!query.data_inicial"
                                :rules="[
                                    dataFinalFiltrolRules.Valido(
                                        query.data_final,
                                        query.data_inicial
                                    ),
                                ]"
                                :error-messages="erros.data_fim_real"
                                dense
                            >
                            </v-text-field>
                        </v-col> -->
                        <v-col class="text-right">
                            <v-btn
                                outlined
                                rounded
                                title="Adicionar novo projecto"
                                class="font-weight-bold"
                                @click="carregarDialog()"
                                >Pesquisar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-title>
            </v-card>
            <v-spacer></v-spacer>

            <v-card class="elevation-0">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="12"> </v-col>
                    </v-row>

                    <v-data-iterator
                        :items="blocos"
                        :items-per-page.sync="itemsPerPage"
                        :page.sync="page"
                        :search="search"
                        hide-default-footer
                    >
                        <template v-slot:default="props">
                            <v-row align="center" justify="center" dense>
                                <v-col
                                    v-for="item in props.items"
                                    :key="item.nome_proj"
                                    @click="tarefasProjecto(item.id)"
                                    cols="12"
                                    sm="12"
                                    md="6"
                                    :lg="blocos.length <= 3 ? 6 : 3"
                                >
                                    <!--  <v-card
                                        class="my-4white--text"
                                        style="background-color: #19a1be"
                                    >
                                        <v-img
                                            class="my-4 elevation-6"
                                            :src="'/storage/' + item.arquivos"
                                            :height="
                                                blocos.length <= 4 ? 70 : 200
                                            "
                                        >
                                            <v-card-title
                                                class="subheading font-weight-bold my-0 py-0"
                                            >
                                                <v-chip-group column>
                                                    <v-row dense>
                                                        <v-col
                                                            cols="12"
                                                            sm="12"
                                                            md="12"
                                                            order-sm="12"
                                                            offset-md="12"
                                                            class="my-0 white--text"
                                                        >
                                                            {{ item.nome_proj }}
                                                        </v-col>

                                                        <v-col
                                                            cols="12"
                                                            sm="12"
                                                            md="12"
                                                            order-sm="12"
                                                            offset-md="12"
                                                        >
                                                            <v-chip
                                                                class="mx-0 text-h6 back--text font-weight-bold"
                                                                medium
                                                                v-if="
                                                                    item
                                                                        .estado_bloco
                                                                        .designacao
                                                                "
                                                                :class="`estado ${item.estado_bloco.designacao} text-h6 back--text font-weight-bold`"
                                                                >Estado:
                                                                {{
                                                                    item
                                                                        .estado_bloco
                                                                        .designacao
                                                                }}
                                                            </v-chip>
                                                        </v-col>
                                                    </v-row>
                                                </v-chip-group>
                                            </v-card-title>
                                        </v-img>
                                        <v-divider
                                            class="mx-4 my-0 pa-0"
                                        ></v-divider>

                                        <v-card-actions
                                            class="center"
                                            align="center"
                                        >
                                            <v-btn
                                                color="headercolor"
                                                text
                                                disabled
                                            >
                                                Detalhes
                                            </v-btn>
                                            <v-btn
                                                icon
                                                color="green"
                                                v-on:click.stop="
                                                    verDetalhe(item)
                                                "
                                                title="Visualizar Projecto"
                                            >
                                                <v-icon small
                                                    >visibility</v-icon
                                                >
                                            </v-btn>
                                            <v-btn
                                                icon
                                                color="orange"
                                                v-on:click.stop="editItem(item)"
                                                title="Editar "
                                            >
                                                <v-icon small>edit</v-icon>
                                            </v-btn>
                                            <v-btn
                                                icon
                                                v-on:click.stop="
                                                    deleteItem(item)
                                                "
                                                color="red"
                                                title="eliminar"
                                                :disabled="
                                                    item.estado_bloco_id == 2
                                                "
                                            >
                                                <v-icon small
                                                    >mdi-delete</v-icon
                                                >
                                            </v-btn>
                                            <v-btn
                                                :disabled="!item.arquivos"
                                                icon
                                                v-on:click.stop="
                                                    redirect(item.arquivos)
                                                "
                                                color="blue"
                                                title="Visualizar a documentação do bloco"
                                            >
                                                <v-icon small
                                                    >attach_file</v-icon
                                                >
                                            </v-btn>
                                            <v-btn
                                                small
                                                icon
                                                class="mr-2"
                                                v-on:click.stop="
                                                    activarProjectoProducao(
                                                        item
                                                    )
                                                "
                                                color="blue"
                                                title="Ativar o projecto para produção"
                                                :disabled="
                                                    item.estado_producao_id == 1
                                                "
                                            >
                                                <v-icon small>check</v-icon>
                                            </v-btn>
                                            <v-btn
                                                icon
                                                small
                                                v-on:click.stop="
                                                    desactivarProjectoProducao(
                                                        item
                                                    )
                                                "
                                                color="red"
                                                title="Desactivar o projecto em produção"
                                                :disabled="
                                                    item.estado_producao_id == 2
                                                "
                                            >
                                                <v-icon small>close</v-icon>
                                            </v-btn>
                                            <v-menu
                                                transition="slide-x-transition"
                                                bottom
                                                right
                                            >
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-btn
                                                        color="black"
                                                        dark
                                                        icon
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        title="Adicionar responsaveis"
                                                    >
                                                        <v-icon
                                                            >mdi-dots-vertical</v-icon
                                                        >
                                                    </v-btn>
                                                </template>

                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <a
                                                                icon
                                                                color="orange"
                                                                class="text-decoration-none"
                                                                @click="
                                                                    adicionarResponsavel(
                                                                        item
                                                                    )
                                                                "
                                                                title="Adicionar Responsavel"
                                                            >
                                                                Adicionar
                                                                Responsavel
                                                            </a>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>

                                        </v-card-actions>
                                    </v-card> -->

                                    <v-card
                                        class="mx-auto"
                                        color="#0E85A3"
                                        dark
                                        max-width="400"
                                    >
                                        <v-card-title
                                            style="background-color: #19a1be"
                                        >
                                            <v-icon large center>
                                                mdi mdi-home-city
                                            </v-icon>
                                            <!--       <span class="text-h6 font-weight-light">Twitter</span> -->
                                        </v-card-title>

                                        <div class="text-h6 ml-1">
                                            <div>
                                                Bloco
                                                <span class="font-weight-bold"
                                                    >{{ item.descricao_bloco }}</span
                                                >
                                            </div>
                                            <div>
                                                Sindico
                                                <span class="font-weight-bold"
                                                    >{{item.sindico.nome_pessoa}} {{item.sindico.sobre_nome_pessoa}}</span
                                                >
                                            </div>
                                            <div>
                                                Nº de Apartamentos
                                                <span class="font-weight-bold"
                                                    >{{item.numero_apartamento}}</span
                                                >
                                            </div>
                                        </div>

                                        <v-card-actions>
                                            <v-list-item class="grow">
                                                <!--        <v-list-item-avatar color="grey darken-3">
          <v-img
            class="elevation-6"
            alt=""
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-item-avatar>-->

                                                <v-list-item-content>
                                                    <v-list-item-title></v-list-item-title>
                                                </v-list-item-content>

                                                <v-row
                                                    align="right"
                                                    justify="end"
                                                >
                                                    <v-btn
                                                        style="
                                                            background-color: #19a1be;
                                                        "
                                                        rounded
                                                        dark
                                                    >
                                                        Ver
                                                    </v-btn>
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
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                outlined
                                                class="ml-2"
                                                v-bind="attrs"
                                                v-on="on"
                                            >
                                                {{ itemsPerPage }}
                                                <v-icon
                                                    >mdi-chevron-down</v-icon
                                                >
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item
                                                v-for="(
                                                    number, index
                                                ) in itemsPerPageArray"
                                                :key="index"
                                                @click="
                                                    updateItemsPerPage(number)
                                                "
                                            >
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
                                    <v-btn
                                        small
                                        fab
                                        text
                                        color="corprincipal"
                                        class="mr-1"
                                        @click="formerPage"
                                    >
                                        <v-icon>mdi-chevron-left</v-icon>
                                    </v-btn>
                                    <v-btn
                                        small
                                        fab
                                        text
                                        color="corprincipal"
                                        class="ml-1"
                                        @click="nextPage"
                                    >
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
                    <v-toolbar
                        class="text-uppercase font-weight-bold"
                        elevation="0"
                    >
                        <v-toolbar-title>
                            {{
                                editedIndex == -1
                                    ? "Adicionar Bloco"
                                    : "Atualizar Bloco"
                            }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-icon color="red" @click="cancelarDialog"
                            >mdi-close</v-icon
                        >
                    </v-toolbar>
                    <v-card-text>
                     
                            <v-stepper flat v-model="e1">
                          

                                <v-stepper-items flat>
                                    <v-stepper-content step="1" class="pa-1">
                                        <v-card class="mb-12" flat>
                                            <v-form
                                                ref="formbloco"
                                                lazy-validation
                                            >
                                                    <v-card-text>
                                                        <div>
                                                            <v-row>
                                              
                                              <v-col cols="5">
                                               
                                                  <v-text-field
                                                  outlined
                                                  dense
                                                      v-model="
                                                          bloco.designacao
                                                      "
                                                      label="Designação do bloco"
                                                      class="pb-4"
                                                  >
                                                  </v-text-field>
                                              </v-col>
                                              <v-col cols="3">
                                               
                                                  <v-text-field
                                                  outlined
                                                  dense
                                                      v-model="
                                                          bloco.numero_apartamento
                                                      "
                                                      label="número de apartamentos"
                                                      hide-details="auto"
                                                      class="pb-4"
                                                  >
                                                  </v-text-field>
                                              </v-col>
                                         
                                        <v-col
                                                  xs="12"
                                                  sm="12"
                                                  md="4"
                                              >
                                                  <v-autocomplete
                                                  outlined
                                                  dense
                                                      v-model="
                                                          bloco.sindico_id
                                                      "
                                                      :items="
                                                          gestores
                                                      "
                                                      item-value="id"
                                                      item-text="nome_pessoa"
                                                      :rules="
                                                          responsavelRules
                                                      "
                                                      :error-messages="
                                                          erros.responsavel_id
                                                      "
                                                      :menu-props="{
                                                          bottom: true,
                                                          offsetY: true,
                                                      }"
                                                      label="Sindico!"
                                                  />
                                              </v-col>
                                          </v-row>
                                                        
                                                            <v-textarea
                                                                rows="3"
                                                                v-model="
                                                                    bloco.descricao_bloco
                                                                "
                                                                outlined
                                                                autocomplete="Descrição do bloco"
                                                                label="Descrição do bloco"
                                                                :rules="
                                                                    descricaoBlocoRules
                                                                "
                                                                :error-messages="
                                                                    erros.descricao_bloco
                                                                "
                                                            ></v-textarea>
                                                           
                                                            <v-row>
                                                             
                                                                <!-- ocultar a opção de alterar o estado quando estiver a enserir e aumentar o tacmanho da coluna  -->
                                                            </v-row>
                                                     <!--        <v-row dense>
                                                                <v-col cols="4">
                                                                    <template>
                                                                        <v-file-input
                                                                            label="foto do projecto"
                                                                            dense
                                                                            accept="image/*"
                                                                            v-model="
                                                                                bloco.arquivos
                                                                            "
                                                                        ></v-file-input>
                                                                    </template>
                                                                </v-col>
                                                                <v-col cols="4">
                                                                    <template>
                                                                        <v-file-input
                                                                            label="Arquivo "
                                                                            dense
                                                                            accept="application/pdf,image/jpeg,image/png"
                                                                            v-model="
                                                                                bloco.arquivos_dois
                                                                            "
                                                                        ></v-file-input>
                                                                    </template>
                                                                </v-col>
                                                                <v-col cols="4">
                                                                    <template>
                                                                        <v-file-input
                                                                            label="arquivo opcional 2"
                                                                            prefix="imagem"
                                                                            dense
                                                                            accept="application/pdf,image/jpeg,image/png"
                                                                            v-model="
                                                                                bloco.arquivos_tres
                                                                            "
                                                                        ></v-file-input>
                                                                    </template>
                                                                </v-col>
                                                            </v-row> -->
                                                        </div>
                                                    </v-card-text>
                                                    <v-flex
                                                        top
                                                        class="text-right"
                                                    ></v-flex>
                                                    <!-- <v-card-actions
                                                        class="justify-end"
                                                    >
                                                        <v-btn
                                                            color="red"
                                                            class="white--text"
                                                            @click="
                                                                cancelarDialog()
                                                            "
                                                            >Cancelar</v-btn
                                                        >
                                                        <v-btn
                                                            color="#00897b"
                                                            class="white--text"
                                                            @click="save()"
                                                            >{{
                                                                editedIndex > -1
                                                                    ? "Actualizar"
                                                                    : "adicionar"
                                                            }}</v-btn
                                                        >
                                                    </v-card-actions> -->
<!-- 
                                                    <v-btn
                                                        color="#00897B"
                                                        dark
                                                        @click="
                                                            continuar(
                                                                2,
                                                                'formbloco'
                                                            )
                                                        "
                                                    >
                                                        Continuar<v-icon
                                                            >keyboard_arrow_right</v-icon
                                                        >
                                                    </v-btn> -->
                                                    <v-spacer />
                                                    <div class="text-right">
                                                        <v-btn
                                                      
                                                      color="teal"
                                                      dark
                                                      @click="save()"
                                                      v-if="
                                                          editedIndex ==
                                                          -1
                                                      "
                                                      >Guardar</v-btn
                                                  >
                                                  <v-btn
                                                      color="teal"
                                                      dark
                                                      @click="save()"
                                                      v-if="
                                                          editedIndex > -1
                                                      "
                                                      >Actualizar</v-btn
                                                  >
                                                    </div>
                                           
                                            </v-form>
                                        </v-card>
                                    </v-stepper-content>

                                    <v-stepper-content step="2">
                                        <v-card class="mb-12" flat>
                                            <v-form ref="form2" lazy-validation>
                                                <v-container>
                                                    <v-row
                                                        v-if="
                                                            bloco.tipo_projecto_id ==
                                                                1 ||
                                                            bloco.tipo_projecto_id ==
                                                                2
                                                        "
                                                    >
                                                        <v-col
                                                            cols="12"
                                                            md="12"
                                                        >
                                                            <v-autocomplete
                                                                prepend-icon="assignment_ind"
                                                                multiple
                                                                dense
                                                                v-model="
                                                                    BackEnd
                                                                "
                                                                required
                                                                label="BackEnd (*)"
                                                                :items="
                                                                    tecnologiasbackEnd
                                                                "
                                                                item-value="id"
                                                                item-text="designacao"
                                                            >
                                                            </v-autocomplete>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="12"
                                                        >
                                                            <v-autocomplete
                                                                prepend-icon="assignment_ind"
                                                                multiple
                                                                dense
                                                                required
                                                                v-model="
                                                                    frontEnd
                                                                "
                                                                label="FrontEnd (*)"
                                                                :items="
                                                                    tecnologiasFrontEnd
                                                                "
                                                                item-value="id"
                                                                item-text="designacao"
                                                            >
                                                            </v-autocomplete>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="12"
                                                        >
                                                            <v-autocomplete
                                                                prepend-icon="assignment_ind"
                                                                multiple
                                                                dense
                                                                required
                                                                v-model="
                                                                    baseDados
                                                                "
                                                                label="SGBD (*)"
                                                                :items="
                                                                    tecnologiasBasedados
                                                                "
                                                                item-value="id"
                                                                item-text="designacao"
                                                            >
                                                            </v-autocomplete>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row v-else>
                                                        <v-col>
                                                            <v-autocomplete
                                                                prepend-icon="assignment_ind"
                                                                multiple
                                                                dense
                                                                required
                                                                v-model="
                                                                    infraBi
                                                                "
                                                                label="Tecnologias (*)"
                                                                :items="
                                                                    tecnologiasInfraBd
                                                                "
                                                                item-value="id"
                                                                item-text="designacao"
                                                            >
                                                            </v-autocomplete>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="6"
                                                        >
                                                            <v-text-field
                                                                prepend-icon="calendar_today"
                                                                v-model="
                                                                    bloco.dat_inicio_real
                                                                "
                                                                label="Data de início do projecto(*)"
                                                                :rules="
                                                                    dataInicioRules
                                                                "
                                                                :error-messages="
                                                                    erros.dat_inicio_real
                                                                "
                                                                type="date"
                                                                dense
                                                                required
                                                            >
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    bloco.dat_fim_real
                                                                "
                                                                label="Data Final do projecto(*)"
                                                                type="date"
                                                                :rules="[
                                                                    dataFimRealRules.Valido(
                                                                        bloco.dat_fim_real,
                                                                        bloco.dat_inicio_real
                                                                    ),
                                                                ]"
                                                                :error-messages="
                                                                    erros.dat_fim_real
                                                                "
                                                                dense
                                                                required
                                                            >
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-card-actions>
                                                        <v-btn
                                                            color="warning"
                                                            dark
                                                            @click="e1 = 1"
                                                        >
                                                            <v-icon
                                                                >keyboard_arrow_left</v-icon
                                                            >Voltar
                                                        </v-btn>
                                                        <v-spacer />

                                                        <v-btn
                                                            color="teal"
                                                            dark
                                                            @click="save()"
                                                            v-if="
                                                                editedIndex ==
                                                                -1
                                                            "
                                                            >Guardar</v-btn
                                                        >
                                                        <v-btn
                                                            color="teal"
                                                            dark
                                                            @click="save()"
                                                            v-if="
                                                                editedIndex > -1
                                                            "
                                                            >Actualizar</v-btn
                                                        >
                                                    </v-card-actions>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-stepper-content>
                                </v-stepper-items>
                            </v-stepper>
                    
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-dialog
                v-if="dialogDetalhebloco"
                v-model="dialogDetalhebloco"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
            >
                <v-card class="grey lighten-5">
                    <v-toolbar class="text-h6 text-dark dark">
                        <v-spacer></v-spacer>
                        Detalhes do Projecto
                        <v-btn icon dark @click="dialogoDetalhebloco()">
                            <v-icon color="black">mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-actions>
                        <v-btn
                            color="black"
                            text
                            :href="
                                '/projectos/arquivo-pdf-projecto/' + bloco.id
                            "
                            target="__blank"
                            class="remover-link"
                        >
                            Exportar Dados:
                            <v-icon title="Exportar em pdf" color="black">
                                mdi-file-export</v-icon
                            >
                        </v-btn>
                    </v-card-actions>
                    <v-container>
                        <v-card>
                            <v-list three-line subheader>
                                <v-row justify="center">
                                    <v-col cols="12" sm="10" class="ma-3 pa-0">
                                        <v-sheet class="ma-0 pa-0">
                                            <v-subheader
                                                class="font-weight-bold ml-0 mb-2 teal--text text-uppercase"
                                            >
                                                <span
                                                    style="font-weight: bolder"
                                                >
                                                    Dados do Projecto
                                                </span>
                                            </v-subheader>
                                            <v-divider class="ma-0"></v-divider>
                                            <v-list-item>
                                                <v-list-item-content class="">
                                                    <v-list-item-title
                                                        class="font-weight-bold"
                                                        >Nome</v-list-item-title
                                                    >
                                                    <v-list-item-subtitle
                                                        class="black--text"
                                                        >{{
                                                            bloco.nome_proj
                                                        }}</v-list-item-subtitle
                                                    >
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold"
                                                        >Estado</v-list-item-title
                                                    >
                                                    <v-list-item-subtitle
                                                        class="black--text"
                                                        >{{
                                                            bloco
                                                                .estado_bloco
                                                                .designacao
                                                        }}</v-list-item-subtitle
                                                    >
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold"
                                                        >Data de
                                                        Criação</v-list-item-title
                                                    >
                                                    <v-list-item-subtitle
                                                        class="black--text"
                                                    >
                                                        {{
                                                            bloco.dat_inicio_real
                                                        }}</v-list-item-subtitle
                                                    >
                                                </v-list-item-content>
                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold"
                                                        >Data de
                                                        Produção</v-list-item-title
                                                    >
                                                    <v-list-item-subtitle
                                                        class="black--text"
                                                    >
                                                        {{
                                                            bloco.dat_fim_real
                                                        }}</v-list-item-subtitle
                                                    >
                                                </v-list-item-content>

                                                <v-list-item-content>
                                                    <v-list-item-title
                                                        class="font-weight-bold"
                                                        >Gestor de
                                                        Projectos</v-list-item-title
                                                    >
                                                    <v-list-item-subtitle
                                                        class="black--text"
                                                    >
                                                        {{
                                                            bloco.responsavel
                                                                .nome_responsavel
                                                        }}</v-list-item-subtitle
                                                    >
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-sheet>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-card elevation="0">
                                    <v-card-actions class="justify-center">
                                        <v-text
                                            class="text-h6 teal--text"
                                            @click="show = !show"
                                        >
                                            Clique para ver a descrição do
                                            projecto
                                        </v-text>

                                        <v-btn icon @click="show = !show">
                                            <v-icon>{{
                                                show
                                                    ? "mdi-chevron-up"
                                                    : "mdi-chevron-down"
                                            }}</v-icon>
                                        </v-btn>
                                    </v-card-actions>

                                    <v-expand-transition v-model="panel_motivo">
                                        <div v-show="show">
                                            <v-divider></v-divider>

                                            <v-card-text
                                                class="text-justify white text--corprincipal"
                                                v-html="bloco.descricao_proj"
                                            >
                                            </v-card-text>
                                        </div>
                                    </v-expand-transition>
                                </v-card>
                            </v-list>
                        </v-card>

                        <v-card v-if="bloco.equipa_projecto" class="">
                            <v-card-actions class="pa-0">
                                <v-subheader
                                    class="font-weight-bold ml-0 mb-2 teal--text text-uppercase"
                                    ><span style="font-weight: bolder"
                                        >Dados dos Membros
                                    </span></v-subheader
                                >

                                <v-spacer></v-spacer>

                                <v-btn icon @click="show2 = !show2">
                                    <v-icon>{{
                                        show2
                                            ? "mdi-chevron-up"
                                            : "mdi-chevron-down"
                                    }}</v-icon>
                                </v-btn>
                            </v-card-actions>
                            <!-- :items="bloco.equipa_projecto" -->

                            <v-expand-transition>
                                <div v-show="show2">
                                    <v-data-table
                                        :headers="headersResponsavelDetalhes"
                                        :items="this.equipeblocos"
                                        hide-default-footer
                                    >
                                        <template
                                            v-slot:item.actions="{ item }"
                                        >
                                            <v-icon
                                                small
                                                @click="deleteResponsavel(item)"
                                            >
                                                mdi-delete
                                            </v-icon>
                                        </template>
                                        <template slot="no-data">
                                            sem nenhum dado
                                        </template>
                                        <template slot="no-results">
                                            não foi encontrado nehum dado na
                                            pesquisa
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-expand-transition>
                        </v-card>
                    </v-container>
                    <div style="flex: 1 1 auto"></div>
                </v-card>
            </v-dialog>

            <v-dialog
                v-if="dialogDelete"
                v-model="dialogDelete"
                max-width="550px"
            >
                <v-card>
                    <v-card-title class="text-h6"
                        >Tens a certeza que pretendes eliminar este
                        projecto?</v-card-title
                    >
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn rounded outlined color="red" dark @click="closeDelete">não</v-btn>
                        <v-btn rounded outlined color="#00897b" dark @click="deleteItemConfirm"
                            >sim</v-btn
                        >
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-row>
                <v-col cols="12" md="4">
                    <v-btn
                        bottom
                        color="corprincipal"
                        fab
                        fixed
                        right
                        @click="carregarDialog()"
                    >
                        <v-icon class="white--text">add </v-icon>
                    </v-btn>
                </v-col>
            </v-row>
            <v-dialog
                v-if="dialogResponsavel"
                v-model="dialogResponsavel"
                max-width="500px"
            >
                <v-card>
                    <v-toolbar class="font-weight-bold" elevation="2">
                        <v-toolbar-title>
                            Adicionar responsáveis ao projecto
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-form ref="formResponsavel" lazy-validation>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-autocomplete
                                        v-model="bloco_responsavel.funcao_id"
                                        :items="funcaoEquipe"
                                        item-text="designacao"
                                        item-value="id"
                                        prepend-icon=""
                                        @change="filtrarResponsavelbloco()"
                                        :menu-props="{
                                            bottom: true,
                                            offsetY: true,
                                        }"
                                        label="Funcao"
                                    />
                                </v-col>
                                <v-col cols="12" md="12">
                                    <v-autocomplete
                                        multiple
                                        v-model="
                                            bloco_responsavel.responsavel_id
                                        "
                                        :items="responsavelblocos"
                                        item-text="nome_responsavel"
                                        item-value="id"
                                        prepend-icon="person"
                                        :menu-props="{
                                            bottom: true,
                                            offsetY: true,
                                        }"
                                        label="Adicionar mais responsaveis"
                                    >
                                        <template v-slot:item="data">
                                            {{ data.item.nome_responsavel }}
                                            {{
                                                data.item.sobre_nome_responsavel
                                            }}
                                        </template>
                                    </v-autocomplete>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="red"
                            dark
                            @click="cancelarDialogResponsavel()"
                            >Cancel</v-btn
                        >
                        <v-btn
                            v-if="
                                bloco_responsavel.responsavel_id.length != 0
                            "
                            dark
                            color="#00897B"
                            @click="saveResponsavel()"
                            >Adicionar</v-btn
                        >
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
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
        // "equipa_projecto",
        "filtroGestores",
        "filtroProjectos",
        //  "tecnologiasbackEnd",
        //"tecnologiasFrontEnd",
        // "tecnologiasBasedados",
        "categorias",
        "tipoProjectos",
    ],
    components: {
        AppLayout,
        DatePicker,
    },

    data() {
        return {
            show2: "",
            show: "",
            dialog: false,
            dialogDetalhebloco: false,
            dialogDelete: false,
            bloco: {
                responsavel: {},
                arquivos: "",
               
            },
            equipa_projecto_id: {},
            deleteresponsavel: {},
            deletedIndex: -1,
            input: 1,

            e1: 1,
            panel_motivo: "",
            editedIndex: -1,
            erros: [],
            // date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            projprioridade: ["Baixo", "Medio", "Alto"],
            itemsPerPageArray: [8, 16, 24, 32],
            page: 1,
            itemsPerPage: 8,
            sortBy: "",
            sortDesc: false,
            search: "",
            filter: {},
            baseDados: [],
            infraBi: [],
            frontEnd: [],
            backEnd: [],
            categoria: {
                area_empresa_id: null,
            },
            equipeblocos: {},
            projectoTecnologia: [],
            tecnologias: [],
            responsavelblocos: [],
            funcaoMembros: [],
            dialogResponsavel: false,
            dialogDeleteResponsavel: false,
            bloco_responsavel: {
                responsavel_id: [],
                funcao_id: [],
                responsavel_id: [],
            },

            funcao_responsavel: {
                funcao_id: [],
            },

            query: {
                estado_bloco_id: "",
                projecto_id: "",
                data_final: "",
                data_inicial: [],
                id_responsavel: [],
            },
            dataFinalFiltrolRules: {
                Valido(value, data) {
                    return (
                        value >= data ||
                        "A data final não pode ser inferior que a data inicial"
                    );
                },
            },
            headersResponsavelDetalhes: [
                {
                    text: "Nomes dos Responsaveis",
                    value: "user.name",
                },
                {
                    text: "Função",
                    value: "funcao.designacao",
                },
                {
                    text: "Opções",
                    value: "actions",
                    align: "left",
                    class: "font-weight-bold black--text subtitle-1 my-3",
                    sortable: false,
                },
            ],
            defaultbloco: {
                arquivos: "",
              
            },

            nomeblocoRules: [(v) => !!v || "Campo obrigatório!"],
            responsavelRules: [(v) => !!v || "Capmo Obrigatorio"],
            prioridadeblocoRules: [(v) => !!v || "Capmo Obrigatorio"],
            estadoblocoRules: [(v) => !!v || "Capmo Obrigatorio"],

            dataFimRealRules: {
                Valido(value, data) {
                    return (
                        value > data ||
                        "A data de Final tem de ser maior que a data! dados"
                    );
                },
            },
            dataInicialfiltroRules: [(v) => !!v || "Dados Obrigatório"],

            dataInicioRules: [
                (v) => !!v || "Data Obrigatório",
                // (v) =>
                //     v >= new Date().toISOString().substr(0, 10) ||
                //     "Data Inicial Invalida!",
            ],

            descricaoProjectoRules: [(v) => !!v || "Campo Obrigatório"],
        };
    },
    watch: {
        dialog1(val) {
            if (val) return;

            setTimeout(() => (this.dialog1 = false), 4000);
        },
    },
    created() {
        this.equipeProjectos(this.deleteresponsavel);
        // alert(JSON.stringify(this.tecnologiasbackEnd) );
        // this.verDetalhe(this.deleteresponsavel);
    },

    computed: {
        numberOfPages() {
            return Math.ceil(this.blocos.length / this.itemsPerPage);
        },
        // filteredKeys() {
        //   return this.keys.filter((key) => key !== "nome");
        // },
    },

    methods: {
        dialogoDetalhebloco() {
            this.dialogDetalhebloco = false;
            this.show2 = "";
        },
        tarefasProjecto(item) {
            this.$inertia.get(
                "/blocos/apartamento/" + btoa(btoa(btoa(item)))
            );
        },

        validate() {
            this.$refs.formbloco.validate();
        },

        continuar(stepe, formbloco) {
            if (this.$refs[formbloco].validate()) {
                this.e1 = stepe;
            }
        },
        filtrarTipoProjectos() {
            this.categoria.area_empresa_id = this.bloco.categoria_id;
            axios
                .get("/projectos/filtrar-tipo-projectos", {
                    params: this.categoria,
                })
                .then((response) => {
                    this.tipoProjectos = response.data;
                    //   blocos
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        filtrarTecnologia() {
            this.categoria.tecnologia_id = this.bloco.tipo_projecto_id;
            axios
                .get("/projectos/filtrar-tecnologia", {
                    params: this.categoria,
                })
                .then((response) => {
                    this.tecnologiasbackEnd = response.data.tecnologiasbackEnd;
                    this.tecnologiasFrontEnd =
                        response.data.tecnologiasFrontEnd;
                    this.tecnologiasBasedados =
                        response.data.tecnologiasBasedados;
                    this.tecnologiasInfraBd = response.data.tecnologiasInfraBd;
                    //   blocos
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
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
            // ' + user.foto" v-if="user.foto
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
            // let searchServico = this.servicos_por_pagar.find(function (elemento) {
            //     return elemento.id == servico_id;
            // });
            //Percorrer o item selecionado
            item.control_projecto_tecnologia.forEach((elemento) => {
                this.projectoTecnologia.push(elemento.tecnologia);
            });
            //Percorrer a v-modal BackEnd
            this.projectoTecnologia.forEach((element) => {
                this.backEnd.push(element.id);
            });
            //Percorrer a v-modal FrontEnt

            this.projectoTecnologia.forEach((element) => {
                this.frontEnd.push(element.id);
            });
            //Percorrer a v-model Base de Dados
            this.projectoTecnologia.forEach((element) => {
                this.baseDados.push(element.id);
            });
        },
        deleteItem(item) {
            this.editedIndex = this.blocos.indexOf(item);
            this.bloco = Object.assign({}, item);
            this.dialogDelete = true;
        },
        deleteResponsavel(item) {
            this.deleteresponsavel = Object.assign({}, item);
            // this.equipeblocos.splice(item, 1);
            // alert(JSON.stringify(this.equipeblocos.splice(item, 1)))

            this.$inertia.delete(
                "/projectos/equipe-projectos/" + this.deleteresponsavel.id,
                {
                    onFinish: () => {
                        Vue.toasted.global.defaultSuccess({
                            msg: " " + this.$page.props.flash.success,
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                }
            );
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
            this.backEnd = [];
            this.frontEnd = [];
            this.baseDados = [];
            this.infraBi = [];
            this.projectoTecnologia = [];
            this.bloco_responsavel = {};
            this.e1 = 1;
        },

        closeSave() {
            this.bloco = Object.assign({}, this.defaultbloco);
            this.editedIndex = -1;
            this.dialog = false;
            this.backEnd = [];
            this.frontEnd = [];
            this.baseDados = [];
            this.infraBi = [];
            this.projectoTecnologia = [];
            this.e1 = 1;
        },

        pdf(item) {
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

        adicionarResponsavel(item) {
            this.bloco_responsavel.projecto_id = item.id;
            this.dialogResponsavel = true;
        },

        cancelarDialogResponsavel() {
            this.bloco_responsavel = {
                responsavel_id: [],
            };
            this.responsavelblocos = [];
            this.dialogResponsavel = false;
        },
        closeSaveResponsavel() {
            this.bloco = Object.assign({}, this.defaultbloco);
            this.bloco_responsavel = {
                responsavel_id: [],
            };
            this.responsavelblocos = [];
            this.editedIndex = -1;
            this.dialogResponsavel = false;
        },
        saveResponsavel() {
            this.$inertia.post(
                "/projectos/adicionar-responsavel-projecto",
                this.bloco_responsavel,
                {
                    onFinish: () => {
                        this.cancelarDialogResponsavel();
                        if (this.$page.props.flash.success != null) {
                            Vue.toasted.global.defaultSuccess({
                                msg: "" + this.$page.props.flash.success,
                            });
                        }
                        if (this.$page.props.flash.error != null) {
                            Vue.toasted.global.defaultError({
                                msg: "" + this.$page.props.flash.error,
                            });
                            this.cancelarDialogResponsavel();
                        }
                    },
                }
            );
        },
        filtrarResponsavelbloco() {
            axios
                .get("/projectos/filtrar-responsavel-projecto", {
                    params: this.bloco_responsavel,
                })
                .then((response) => {
                    this.responsavelblocos = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },

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
        equipeProjectos(item) {
            this.equipa_projecto_id.id = item.id;
            axios
                .get("/projectos/equipe-projecto", {
                    params: this.equipa_projecto_id,
                })
                .then((response) => {
                    this.equipeblocos = response.data;
                })
                .catch((error) => {
                    //toastr.warning('Houve uma falha ao carregar os dados!...');
                });
        },
        //Esse metodo é para colocar o projecto em etsado de produção para servir de criterio primordial na API do Mutue Help Desk
        activarProjectoProducao(item) {
            this.$inertia.put("/projectos/activar-producao/" + item.id, item, {
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
        },
        desactivarProjectoProducao(item) {
            this.$inertia.put(
                "/projectos/desactivar-producao/" + item.id,
                item,
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
        },
    },
    /*     getColor(estado_id) {
            if (estado_id == 1) {
                return "orage";
            } else if (estado_id == 2) {
                return "blue";
            } else if (estado_id == 3) {
                return "grey";
            } else if (estado_id == 4) {
                return "green";
            } else if (estado_id == 5) {
                return "red";
    } else return "green";
        }, */
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

table {
    font-weight: bold;
}

.v-chip.v-chip.Pendente {
    background: orange !important;
}

.v-chip.Finalizado {
    background-color: #02bb55 !important;
    color: #fff;
}

.v-chip.Atrasado {
    background-color: orangered !important;
    color: #fff;
}

/* .v-chip.Andamento */
.v-chip.Andamento {
    background-color: #00c3ff !important;
    font-size: 0.1rem;
}

/* .headercolor {
    color:#00897B! important;

} */
</style>
