<template>
  <div class="app flex-row align-items-center">
    <b-container>
      <b-row class="justify-content-center">
        <b-col md=6>
          <b-card-group>
            <b-card p=4>
              <b-card-body>
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <b-form @submit.prevent="authLogin()" @input="login.success = null">
                  <b-form-group>
                    <label>Username</label>
                    <b-form-input
                          v-model="login.name"
                          :state="login.success"
                          type="text"
                          placeholder="Username">
                    </b-form-input>
                    <b-form-invalid-feedback>
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                        <span v-if="login.success==false">
                            Incorrect username or password.
                        </span>
                    </b-form-invalid-feedback>
                  </b-form-group>
                  <b-form-group>
                    <label>Password</label>
                    <b-form-input
                          v-model="login.password"
                          :state="login.success"
                          type="password"
                          placeholder="Password">
                    </b-form-input>
                  </b-form-group>
                  <b-row>
                    <b-col>
                      <b-btn type="submit" variant="primary" px-4>Login</b-btn>
                    </b-col>
                  </b-row>
                </b-form>
              </b-card-body>
            </b-card>
          </b-card-group>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data(){
    return{
      login: {
        name: null,
        password: null,
        success: null,
      }
    }
  },
  methods: {
    authLogin(){
       this.$http.post('api/auth/login', { 
                    name: this.login.name,
                    password: this.login.password
                }).then(response => {
                    this.$store.commit('loginUser')
                    this.$store.commit('user', response.data.user.original)
                    localStorage.setItem('token', response.data.access_token)
                    this.$router.push({ name: 'Dashboard' })
       }).catch(err => {
            this.login.success = false
       });
    }
  }
}
</script>
