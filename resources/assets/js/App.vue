<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: 'app',
  created() {
            if(localStorage.token) {
                this.$http.get('/api/user', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                },
                ).then(response => {
                    this.$store.commit('loginUser')
                    this.$store.commit('user', response.data)
                }).catch(error => {
                    if (error.response.status === 401 || error.response.status === 403) {
                        this.$store.commit('logoutUser')
                        localStorage.setItem('token', '')
                        this.$router.push({name: 'Login'})
                    }

                });
            }

        }
}
</script>

<style>
  $fa-font-path: "~font-awesome/fonts/";
  @import "~font-awesome/css/font-awesome.min.css";
  $simple-line-font-path: "~simple-line-icons/fonts/";
  @import "~simple-line-icons/css/simple-line-icons.css";
</style>
<style lang="scss">
  // Import Main styles for this application
  @import "../scss/style";
</style>
