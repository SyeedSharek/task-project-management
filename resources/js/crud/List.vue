<template>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <router-link :to="{ name: 'Register' }" class="btn btn-primary">Add New User</router-link>
        </div>
        <div class="card-body">
            <div class="input-group mb-3" style="width: 250px; float: right">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search Here"
                    aria-label="Search"
                    aria-describedby="basic-addon1"
                    v-model="searchQuery"
                    @keyup="search"
                />
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">SL</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Address</th>
                        <th scope="col">City</th>
                        <th scope="col">Zip Code</th>
                        <th scope="col">State</th>
                        <th scope="col">Country</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="user.id">
                        <th scope="row">{{ (currentPage - 1) * perPage + index + 1 }}</th>
                        <td>{{ user.first_name }}</td>
                        <td>{{ user.last_name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.address }}</td>
                        <td>{{ user.city }}</td>
                        <td>{{ user.zip_code }}</td>
                        <td>{{ user.state }}</td>
                        <td>{{ user.country }}</td>
                        <td>
                            <router-link
                                :to="{ name: 'EditUser', params: { id: user.id } }"
                                class="btn btn-primary m-1"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </router-link>
                            <a @click="destroy(user.id)" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav v-if="totalPages > 1">
                <ul class="pagination ">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" @click="fetchPage(currentPage - 1)">Previous</a>
                    </li>
                    <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
                        <a class="page-link" @click="fetchPage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <a class="page-link" @click="fetchPage(currentPage + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { showToast } from "../showToast";

export default {
    data() {
        return {
            users: [],
            currentPage: 1,
            totalPages: 1,
            perPage: 5,
            searchQuery: "",
        };
    },
    mounted() {
        this.userList(); // Get user list on mount
    },
    methods: {
        userList(page = 1) {
            this.currentPage = page;
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get(`/api/auth/user/index?page=${page}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.users = response.data.users.data;
                        this.totalPages = response.data.users.last_page;
                    })
                    .catch((error) => {
                        console.log("Data Error", error);
                    });
            } else {
                this.$router.push({ name: "Login" });
            }
        },

        fetchPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.userList(page);
            }
        },

        destroy(user_id) {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .delete(`/api/auth/user/delete/${user_id}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then(() => {
                        this.userList(this.currentPage); // Refresh the current page
                        showToast("User deleted successfully", "success");
                    })
                    .catch((error) => {
                        console.log("Data Error");
                        showToast("Error deleting user", "error");
                    });
            } else {
                this.$router.push({ name: "Login" });
            }
        },

        search() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .post(`/api/auth/user/search?search=${this.searchQuery}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.users = response.data.users.data;
                        this.totalPages = response.data.users.last_page;
                    })
                    .catch((error) => {
                        console.log("Search error:", error);
                        this.users = [];
                    });
            } else {
                this.$router.push({ name: "Login" });
            }
        },
    },
};
</script>

