<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h2>Update User</h2>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateData">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.first_name"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.first_name"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.last_name"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.last_name"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="formData.email"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.email"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="formData.password"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.password"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="formData.password_confirmation"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.password_confirmation"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.address"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.city"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Zip Code</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.zip_code"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.country"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">State</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.state"
                                />
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { showToast } from "../showToast";


export default {
    data() {
        return {
            formData: {
                first_name: "",
                last_name: "",
                email: "",
                password: "",
                password_confirmation: "",
                address: "",
                city: "",
                zip_code: "",
                country: "",
                state: "",
            },
            formError: {},
        };
    },
    mounted() {
        this.getUserData();
    },
    methods: {
        async getUserData() {
            const token = localStorage.getItem("authToken");
            if (!token) {
                this.$router.push({ name: "Login" });
            }

            try {
                const response = await axios.get(
                    `/api/auth/user/show/${this.$route.params.id}`,
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );

                if (response.data && response.data.user) {
                    this.formData = { ...response.data.user, password: "", password_confirmation: "" };
                }
            } catch (error) {
                console.error("Error fetching user data:", error.response?.data || error.message);
            }
        },

         async updateData() {
            const token = localStorage.getItem("authToken");
            if (!token) {
                this.$router.push({ name: "Login" });

            }

            try {
                const response =  await axios.post(
                    `/api/auth/user/update/${this.$route.params.id}`,
                    this.formData,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json",
                        },
                    }
                );

                console.log("Update Success Response:", response);


                showToast(response.data.message, "success");

                this.$router.push({ name: "userList" });

            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.formError = error.response.data.errors;
                } else {
                    console.error("Update error:", error.response?.data || error.message);
                }
            }
        },
    },
};
</script>

