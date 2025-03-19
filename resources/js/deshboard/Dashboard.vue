<template>
    <div class="container-fluid">
        <div class="row p-0">
            <div class="col-2 sidebar p-0">
                <h4>Admin Dashboard</h4>
                <div class="list-group list-group-flush text-bold">
                    <!-- <router-link
                        :to="{ name: 'userList' }"
                        class="list-group-item list-group-item-action list-group-item-dark"
                        active-class="active"
                    >
                        Dashboard
                    </router-link> -->
                    <router-link
                        :to="{ name: 'userList' }"
                        class="list-group-item list-group-item-action list-group-item-dark"
                        active-class="active"
                    >
                        User List
                    </router-link>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-10 main-contain m-0 p-0">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <!-- <a href="#" class="navbar-brand">Task Management Project</a>
                          -->

                          <h1>Task Management</h1>

                        <button
                            @click.prevent="logout"
                            class="btn btn-outline-success"
                            type="submit"
                        >
                            Logout
                        </button>
                    </div>
                </nav>

                <div class="main-view">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: [], // To store user list
        };
    },
    mounted() {
        this.userList(); // Get user list on mount
    },
    methods: {
        logout() {
            const token = localStorage.getItem("authToken");

            axios
                .post("/api/auth/logout", {}, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then(() => {
                    localStorage.removeItem("authToken");
                    this.$router.push({ name: "Login" });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        userList() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/user/index", {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.users = response.data.users;
                    })
                    .catch((error) => {
                        console.log("Data Error", error);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },
    },
};
</script>

<style>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
}
.sidebar {
    height: 100vh;
    background: none;
    background-color: #777;
    overflow-y: auto;
}
.list-group-item {
    background-color: transparent;
    color: #ddd;
}
.logout-btn {
    margin-left: auto;
}
.main-contain {
    height: 100vh;
    overflow-y: auto;
}
</style>
