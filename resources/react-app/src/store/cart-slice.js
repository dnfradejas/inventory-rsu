import { createSlice } from "@reduxjs/toolkit";

const cartSlice = createSlice({
  name: "cart",
  initialState: {
    items: [],
    grandTotal: 0,
    totalQuantity: 0,
  },
  reducers: {
    replaceCart(state, action){
        state.grandTotal = action.payload.grandTotal;
        state.totalQuantity = action.payload.totalQuantity;
        state.items = action.payload.items;
    },
  },
});


export const cartActions = cartSlice.actions;

export default cartSlice;