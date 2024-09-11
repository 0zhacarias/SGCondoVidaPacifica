<template>
  <app-layout>
  
    <div class="dashboard">
        <h1 class="dashboard grey--text">Pojectos</h1>
    <v-container class="my-3">
      <v-card flat class="pa-5" v-for="projeto in projetos" :key="projeto.nomep" >
        <v-layout row wrap :class="'pa-3 projeto ${projeto.estado}'">
          <v-flex xs12 md4>
            <div class="caption grey--text"> Titulo do Projecto</div>
            <div> {{projeto.nomep}}</div>
          </v-flex>
          <v-flex xs6 sm4 md2>
            <div class="caption grey--text">Programador</div>
            <div>{{projeto.actor}}</div>
          </v-flex>
          <v-flex xs6 sm4 md2>
            <div class="caption grey--text"> Data criacao</div>
            <div>{{projeto.datai}}</div>
          </v-flex>
            <v-flex xs6 sm4 md2>
            <div class="caption grey--text"> Data termino</div>
            <div>{{projeto.dataf}}</div>
          </v-flex>
          <v-flex xs6 sm4 md2>
            <div class="caption grey--text"> Estado</div>
            <div>{{projeto.estado}}</div>
          </v-flex>
        </v-layout>
         <v-divider></v-divider>
      </v-card>
     
    </v-container>
    </div>
    <template>
  <v-card
    class="pa-md-4"
    color="secondary"
    width="250px"
  >
    <v-card-text class="pa-3">
      Adjust screen size to see spacing changes
    </v-card-text>
  </v-card>
</template>
  </app-layout>
</template>


<script>
import AppLayout from '../../Shared/AppLayout'

export default {
   data () {
    return{
      projetos:[
        {nomep:'Projetos administrativo da mutue', actor:'Domingos pedri',datai:'11 de 11 de 20022', 
        dataf:'11 de 12 de 2022', estado: 'inicial'},
        {nomep:'Projetos revista mutue', actor:'Zage Milonga',datai:'11 de 11 de 20022', 
        dataf:'11 de 12 de 2022', estado: 'atraso'},
        {nomep:'Projetos Gestao de Notas da mutue', actor:'Mateus Ditutala',datai:'11 de 11 de 20022', 
        dataf:'11 de 12 de 2022', estado: 'teminado'},
         {nomep:'Projetos Gestao de Notas da mutue', actor:'Mateus Ditutala',datai:'11 de 11 de 20022', 
        dataf:'11 de 12 de 2022', estado: 'andamento'}
      ]
    }
    
  },


  computed: {
   
  },

  mounted() {
  
  },
    
  created(){
  
  },

  methods: {

    
         },
    

    progress() {
     
    },


};
</script>

<style>
@import "vuetify/dist/vuetify.min.css";
.projeto.teminado{
  border-left: 5px solid green;
}
.projeto.atrasado{
  border-left: 5px solid orangered;
}
.projeto.inicial{
  border-left: 5px solid gray;
}
.projeto.andamento{
  border-left: 5px solid blue;
}
</style>


 