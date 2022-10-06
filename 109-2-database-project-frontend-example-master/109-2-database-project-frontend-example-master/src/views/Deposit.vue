<template>
  <v-container fluid fill-height class="d-flex align-center">
    <v-row class="d-flex justify-center">
      <v-col cols="3">
      <!-- <section class="dropdown-wrapper"> -->
        <!-- <div @click="isVisible = !isVisible" class="selected-item text-h5 text-top mb-3">尋找您所想要的書籍
        <svg class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
        </div>
        <div v-if="isVisible" class="dropdown-popover">
        <input v-model="searchQuery" type="text" placeholder="Search for User">
        <div class="options">
        <ul>
          <li>微積分</li>
          <li>英文</li>
          <li>國文</li>
          <li>計算機網路</li>
          <li>數位邏輯</li>
        </ul>
      </div>
    </div> -->
        <v-form ref="form" v-model="valid" lazy-validation>
        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M15 4H5v16h14V8h-4V4zM3 2.992C3 2.444 3.447 2 3.999 2H16l5 5v13.993A1 1 0 0 1 20.007 22H3.993A1 1 0 0 1 3 21.008V2.992zm10.529 11.454a4.002 4.002 0 0 1-4.86-6.274 4 4 0 0 1 6.274 4.86l2.21 2.21-1.414 1.415-2.21-2.21zm-.618-2.032a2 2 0 1 0-2.828-2.828 2 2 0 0 0 2.828 2.828z"/></svg> -->
          <v-text-field
            v-model="amount"
            type="text"
            
            label="尋找您所想要的書籍"
            required
            outlined
            solo
            @submit.prevent="handleSubmit"
          ></v-text-field>
          <!-- prepend-inner-icon="mdi-currency-usd " -->
          <!-- :rules="numberRules" -->
          <v-btn type="submit" :disabled="!valid" rounded @click="handleSubmit"
            >GoGoGo</v-btn
          >
        </v-form>
        <!-- <v-snackbar
          v-model="isSuccess"
          :timeout="2000"
          color="success"
          top
          right
        >
          Success
        </v-snackbar> -->
        <!-- </section> -->
      </v-col>
    </v-row>
    <!-- <template>
      <section class="dropdown-wrapper">
    <div @click="isVisible = !isVisible" class="selected-item">
      <span>Select Items</span>
      <svg class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
    </div>
    <div v-if="isVisible" class="dropdown-popover">
      <input v-model="searchQuery" type="text" placeholder="Search for User">
      <div class="options">
        <ul>
          <li>微積分</li>
          <li>英文</li>
          <li>國文</li>
          <li>計算機網路</li>
          <li>數位邏輯</li>
        </ul>
      </div>
    </div>
  </section>
    </template> -->
        <template v-if="!isLoading">
      <v-row v-for="i in rowSize" :key="i">
        <v-col v-for="n in getColoumNumber(i)" :key="n">
          <v-card class="mx-auto my-12" max-width="380" max-height="500">
            <a
              ><v-img
                contain
                max-height="250"
                :src="getImagePath(products[(i - 1) * 3 + n - 1])"
            /></a>
            <v-card-title>{{
              products[(i - 1) * 3 + n - 1].Name
            }}</v-card-title>
            <v-card-subtitle class="text-start">
              NT$ {{ products[(i - 1) * 3 + n - 1].Price }}
            </v-card-subtitle>
            <v-card-text>
              <div class="text-left">
                {{ products[(i - 1) * 3 + n - 1].Description }}
              </div>
            </v-card-text>

            <v-card-actions>
              <span>
                Inventory：{{ products[(i - 1) * 3 + n - 1].Inventory }}
              </span>
              <v-spacer></v-spacer>
              <v-btn
                color="deep-purple lighten-2"
                text
                @click="addToCart(products[(i - 1) * 3 + n - 1])"
              >
                Add to Cart
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </template>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
// @ is an alias to /src
import { imgageHost } from "@/config/config";
import Loading from "@/components/Loading";
export default {
  data() {
    return {
      valid: true,
      amount: "",
      isSuccess: this.IsUpdateSuccess,
      isLoading: true,
      rowSize: 0,
      category: this.$route.query.category,
      // numberRules: [
      //   v => !!v || "Searching is required",
      //   v => /^[1-9]+[0-9]*$/.test(v) || "Value is invalid."
      // ],
      searchQuery: '',
      selectedItem: null,
      isVisible: false,
    };
  },
  // computed: {
  //   ...mapGetters({ IsUpdateSuccess: "wallet/IsUpdateSuccess" })
  // },
    computed: {
    ...mapGetters({ products: "products/Items" })
  },
  watch: {
    $route: async function() {
      this.isLoading = true;
      this.category = this.$route.query.category;
      await this.loadData();
      this.isLoading = false;
    }
  },
  async created() {
    await this.loadData();
    this.isLoading = false;
  },
  // methods: {
  //   async handleSubmit() {
  //     await this.$store.dispatch("wallet/UpdateWallet", this.amount);
  //     this.$refs.form.reset();
  //     this.isSuccess = this.IsUpdateSuccess;
  //   }
  // }

  methods: {
    async loadData() {
      await this.$store.dispatch("products/getAllItems", this.category);
      this.rowSize = Math.ceil(
        this.products.length < 3 ? 1 : this.products.length / 3
      );
    },
    getImagePath(product) {
      if (!product) return "https://www.pixiv.net/artworks/86483702";
      return `${imgageHost}/${product.ImageURL.replace("./img/", "")}`;
    },
    getColoumNumber(index) {
      const length = this.products.length;
      if ((length - index * 3) % 3 >= 0) return 3;
      else if (length - index * 3 < 0) return length - index * 3 + 3;
      else return length - index * 3;
    },
    addToCart(product) {
      this.$store.dispatch("shoppingCart/addProductToCart", product);
    }
  }
};
</script>

<style scoped lang="scss">
.my-from {
  height: 800px;
}

.dropdown-wrapper{
  max-width: 360;
  position: relative;
  margin: 0 auto;

  .selected-item{
    height: 60px;
    border: 2px solid;
    border-radius: 5px;
    padding: 5px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    font-weight: 500;
  }

  .dropdown-popover{
    position: absolute;
    border: 2px solid lightgray;
    top: 46px;
    left: 0;
    right: 0;
    background-color: #fff;
    max-width: 100%;
    padding: 10px;

    input {
      width: 95%;
      height: 30px;
      border:  2px solid lightgray;
      font-size: 16px;
      padding-left: 8px;
    }

    .options{
      width: 100%;

      ul{
        list-style: none;
        text-align: left;
        padding-left: 8px;
        max-height: 100px;
        overflow-y: scroll;
        overflow-x: hidden;

        li{
          width: 100%;
          border-bottom: 1px solid lightgray;
          padding: 10px;
          background-color: #f1f1f1;
          cursor: pointer;
          font-size: 25px;
          font-weight: 600;
          &:hover{
            background-color: rgb(131, 146, 125);
            color: #fff;
            font-weight: bold;
          }
        }
      }
    }
  }
}
</style>