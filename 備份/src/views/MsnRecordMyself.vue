<template>
  <v-container fluid fill-height class="d-flex align-center">
    <v-col class="mb-10">
      <v-row class="my-3" justify="center">
        <h1>寄件備份</h1>
      </v-row>
      <v-row justify="center">
        <v-card outlined max-width="1200">
          <template v-for="(msn, index) in msns">
            <v-list-item :key="index">
              <v-row class="my-1">
                <v-col cols="12" class="text-left" align-self="center">
                 <h4>{{ msn.Date }}</h4> 
                  <span>收件人：{{ msn.Reciever }}</span>
                  <br />
                  <br />
                  <span>標題：{{ msn.Topic }}</span>
                </v-col>
                <v-col cols="12" class="text-left">
                  <span> 內容:<br>{{ msn.Content }}</span>
                  <hr />
                </v-col>
              </v-row>
            </v-list-item>
          </template>
        </v-card>
      </v-row>
    </v-col>
  </v-container>
</template>  

<script>
import { Getmymsn } from "@/api/msnrecordmyself";
import { mapGetters } from "vuex";

export default {
  
  data() {
    return {
      msns: []
    };
  },
  computed: {
    ...mapGetters({ UserId: "login/Id" })
  },
  async created() {
    await this.init();
  },
  methods: {
    async init() {
      this.msn = [];
      this.msns.length = 0;
      this.msns = (await Getmymsn(this.UserId)).data;
    },
  }
};
</script>

<style></style>
