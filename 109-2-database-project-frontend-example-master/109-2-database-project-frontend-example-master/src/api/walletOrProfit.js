import store from "@/store";
import axios from "axios";

export const GetwalletOrProfit = async () => {
  const userRole = store.state.login.user.role;
  let request = "getprofit";
  if (userRole !== "seller") request = "getwallet";
  const formData = new FormData();
  formData.append("request", request);
  try {
    const response = await axios.post("/php/login.php", formData);
    let result = null;
    if (userRole === "seller")
      result = { name: "Search：", value: parseInt(response.data.profit) };
    else result = { name: "hhhhhhh：", value: parseInt(response.data.wallet) };
    return result;
  } catch (err) {
    console.log(err);
  }
};

export const Deposit = async amount => {
  const formData = new FormData();
  formData.append("request", "deposit");
  formData.append("value", amount);
  const res = await axios.post("/php/login.php", formData);
  return res;
};

export const UpdateWallet = async value => {
  const formData = new FormData();
  formData.append("request", "updatewallet");
  formData.append("wallet", value);
  const res = await axios.post("/php/login.php", formData);
  return res;
};

export const UpdateProfit = async value => {
  const userId = store.state.login.user.id;
  const formData = new FormData();
  formData.append("request", "updateprofit");
  formData.append("profit", value);
  formData.append("userno", userId);
  const res = await axios.post("/php/processorder.php", formData);
  return res;
};

// export const SearchProduct = async product => {
//   const formData = new FormData();
//   formData.append("request", "publish");
//   formData.append("name", product.name);
//   formData.append("price", product.price);
//   formData.append("category", product.category);
//   formData.append("description", product.description);
//   formData.append("inventory", product.inventory);
//   formData.append("link", product.imageLink);
//   try {
//     const response = await axios.post("/php/publish.php", formData);
//     return response.data;
//   } catch (err) {
//     console.log(err);
//   }
// };