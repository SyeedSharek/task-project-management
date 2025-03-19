<template>
    <form @submit.prevent="registrationData">
        <div class="custom_form">
            <h6 class="mb-0 text-uppercase">Registration Form</h6>
            <hr />
            <div class="form-group">
                <label for="exampleInputEmail1">First Name</label>
                <input
                    type="text"
                    class="form-control custom-input"
                    placeholder="Enter First Name"
                    v-model="formData.first_name"
                    required
                />
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Last Name</label>
                <input
                    type="text"
                    class="form-control custom-input"
                    placeholder="Enter Last Name"
                    v-model="formData.last_name"
                    required
                />
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input
                    type="email"
                    class="form-control custom-input"
                    placeholder="Enter email"
                    v-model="formData.email"
                    required
                />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input
                    type="password"
                    class="form-control custom-input"
                    placeholder="Password"
                    v-model="formData.password"
                    required
                />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input
                    type="password"
                    class="form-control custom-input"
                    placeholder="Confirm Password"
                    v-model="formData.password_confirmation"
                    required
                />
            </div>


            <div
                class="d-flex align-items-center justify-content-between mt-20px"
            >
                <button type="submit" class="btn btn-primary">
                    Create Account
                </button>
                <div class="card-header">
                    <router-link :to="{ name: 'Login' }" class="btn btn-info"
                        >Already Have Account</router-link
                    >
                </div>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                first_name: "",
                last_name: "",
                email: "",
                password: "",
                password_confirmation: "",

            },


            formError: null,
        };
    },

    methods: {
        registrationData() {
            if (
                this.formData.password !== this.formData.password_confirmation
            ) {
                this.formError = {
                    password_confirmation: ["Passwords do not match."],
                };
                return;
            }
            axios
                .post("/api/register", this.formData)
                .then((response) => {
                    console.log(response);
                    this.$router.push({ name: "Login" });
                })
                .catch((error) => {
                    if (error.response && error.response.data) {
                        this.formError = error.response.data.errors;
                    }
                });
        },


    },
};
</script>
<style>
input {
    width: 120px;
}

.custom-input {
    max-width: 300px;
    margin: 0 auto;
}

.custom_form {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 5px;
    background-color: #f2f2f2;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group label {
    margin-bottom: 5px;
}
</style>
