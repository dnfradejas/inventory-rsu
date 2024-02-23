import {createSlice} from '@reduxjs/toolkit';

const notifSlice = createSlice({
    name: 'notification',
    initialState: {
        notification: null
    },
    reducers: {
        showNotification(state, action) {
            state.notification = {
                title: action.payload.title,
                message: action.payload.message,
                success: action.payload.success,
            };
        },
        resetNotification(state) {
            state.notification = null;
        }
    }
});


export const notifActions = notifSlice.actions;

export default notifSlice;