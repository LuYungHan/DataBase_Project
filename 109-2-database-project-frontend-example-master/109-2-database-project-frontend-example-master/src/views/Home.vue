<template>
  <v-container fluid fill-height class="d-flex align-start">
    <v-row v-if="isLoading">
      <v-col cols="8" offset="2">
        <Loading :is-loading="isLoading" />
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
    <div>
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
    </div>
  </v-container>
</template>

<script>
// @ is an alias to /src
import { imgageHost } from "@/config/config";
import { mapGetters } from "vuex";
import Loading from "@/components/Loading";

export default {
  name: "Home",
  components: { Loading },
  data() {
    return {
      isLoading: true,
      rowSize: 0,
      category: this.$route.query.category,
      searchQuery: '',
      selectedItem: null,
      isVisible: false,
    };
  },
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
.dropdown-wrapper{
  max-width: 350;
  position: relative;
  margin: 0 auto;

  .selected-item{
    height: 40px;
    border: 2px solid;
    border-radius: 5px;
    padding: 5px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 20px;
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
          font-size: 16px;
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
