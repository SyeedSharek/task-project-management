import { createRouter, createWebHashHistory } from "vue-router";
import AddNew from "./../crud/AddNew.vue";
import UserList from "./../crud/List.vue";
import Edit from "./../crud/Edit.vue";
import Login from "./../login/Login.vue";
import Register from "./../login/Register.vue";
import Dashboard from "./../deshboard/Dashboard.vue";


const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,  // Ensure you have imported the Login component correctly
    },
    {
        path: "/",
        name: "Login",
        component: Login,
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        children: [
            {
                path: "/add-new",
                name: "AddNew",
                component: AddNew,
            },

            {
                path: "/edit/:id",
                name: "EditUser",
                component: Edit,
            },
            {
                path: "/user-list",
                name: "userList",
                component: UserList, // Ensure this is the correct component path
            },
        ],
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
