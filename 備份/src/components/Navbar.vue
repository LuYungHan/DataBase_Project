<template>
  <v-main class="mb-10">
    <v-app-bar app flat color=#141124>
      <v-spacer></v-spacer>
      <div><b-dropdown text="Block Level Dropdown" block variant="primary" class="m-2">
      <b-form-select v-model="selected" :options="options">
      <router-link
        v-if="isLogin && IsSeller"
        to="/addBook"
        class="my-router-link pa-1"
      >
      <b-form-select-option class="menu-color-one" value="a">AddBook</b-form-select-option>
      </router-link>
      <router-link
        v-if="isLogin"
        to="/msn"
        class="my-router-link pa-1"
      >
        <b-form-select-option class="menu-color-two" value="b">SentMsn</b-form-select-option>
      </router-link>
      <router-link
        v-if="isLogin"
        to="/msnrecord"
        class="my-router-link pa-1"
      >
<b-form-select-option class="menu-color-one" value="c">MsnRecord</b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        to="/msnrecordmyself"
        class="my-router-link pa-1"
      >
        <b-form-select-option class="menu-color-two" value="d">SentRecord</b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        to="/myproduct"
        class="my-router-link pa-1"
      >
        <b-form-select-option class="menu-color-one" value="e">MyProduct</b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        to="/newproduct"
        class="my-router-link pa-1"
      >
       <b-form-select-option class="menu-color-two" value="f">AddProduct</b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        class="my-router-link"
        to="/orderrecord"
      >
        <b-form-select-option class="menu-color-one" value="g">
          MyOrder
       </b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        class="my-router-link"
        to="/processorder"
      >
        <b-form-select-option class="menu-color-two" value="h">
          ProcessOrder
        </b-form-select-option>
      </router-link>
      
      <router-link
        v-if="isLogin"
        class="my-router-link pa-1"
        to="/shoppingcart"
      >
        <v-badge
          v-if="true"
          :content="CartItemLength"
          :value="CartItemLength"
          overlap
        >
          <b-form-select-option class="menu-color-one" value="i">
            <v-icon color="#FFF">mdi-cart-arrow-down</v-icon>
          </b-form-select-option>
        </v-badge>
      </router-link>
      </b-form-select></b-dropdown></div>
      <div class="pa-1 d-flex flex-row">
        <router-link v-if="!isLogin" class="my-router-link" to="/create">
          <v-btn block>
           <span class="menu-color-three">Create</span> 
          </v-btn>
        </router-link>
        <router-link v-if="!isLogin" class="my-router-link" to="/login">
          <v-btn block>
           <span class="menu-color-three">Login</span> 
          </v-btn>
        </router-link>
        <v-btn v-if="isLogin" block @click="logout">
         <span class="menu-color-three">Logout</span> 
        </v-btn>
      </div>
    </v-app-bar>
    <v-navigation-drawer permanent floating app color=#141124>
      <a href="/home">
        <img id="logo" src="@/img/logo.png" alt="logo" />
      </a>
      <v-list>
        <router-link class="my-router-link" to="/home?category=all" >
          <v-list-item link>
            <v-list-item-content>
              <v-list-item-title class="leftTextColor">ALL</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </router-link>
        <router-link
          v-for="(category, i) in categories"
          :key="i"
          class="my-router-link"
          :to="getUrl(category)"
        >
          <v-list-item link>
            <v-list-item-content>
              <v-list-item-title class="leftTextColor">{{ titleCase(category) }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </router-link>
      </v-list>
    </v-navigation-drawer>

    <router-view />
  </v-main>
</template>

<script>
import { mapGetters } from "vuex";
import { GetProductsCategories } from "@/api/home";
export default {
  data() {
    return {
      categories: [],
      selected: null,
        options: [
          { value: 'a', text: 'A' },
          { value: 'b', text: 'B' },
          { value: 'c', text: 'C' },
          { value: 'd', text: 'D' },
          { value: 'e', text: 'E' },
          { value: 'f', text: 'F' },
          { value: 'g', text: 'G' },
          { value: 'h', text: 'H' },
          { value: 'i', text: 'I' }

        ]
    };
  },
  computed: {
    ...mapGetters({ isLogin: "login/IsLogin" }),
    ...mapGetters({ CartItemLength: "shoppingCart/CartItemLength" }),
    ...mapGetters({ IsSeller: "login/IsSeller" }),
    ...mapGetters({ moneyInfo: "wallet/GetWallet" }),
    getUrl: () =>
      function(category) {
        return `/home?category=${category}`;
      }
  },
  async created() {
    //await this.$store.dispatch("wallet/init");
    this.categories = (await GetProductsCategories()).categories;
  },
  methods: {
    logout() {
      this.$store.dispatch("login/logout");
      this.$router.push("/home").catch(err => {
        err;
      });
    },
    titleCase(str) {
      const newStr = str.slice(0, 1).toUpperCase() + str.slice(1).toLowerCase();
      return newStr;
    }
  }
};
</script>

<style>


#logo {
  width: 250px;
}


.my-router-link {
  text-decoration: none !important;

}
.menu-color-one{

  color: #f0ffff;
  font-size: 20px;
  font-family:Microsoft JhengHei;
  font-weight:bold;
}

.menu-color-two{

  color: #f9d600;
  font-size: 20px;
  font-family:Microsoft JhengHei;
  font-weight:bold;
}
.menu-color-three{
  font-size: 16px;
  font-family:Microsoft JhengHei;
  font-weight:bold;
}

.moneyInfo {
  text-decoration: none !important;
  color: #202020 !important;
}

.leftTextColor {
  color: white ;
}


</style>
