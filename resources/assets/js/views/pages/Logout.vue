<template>

</template>

<script>
    export default {
        mounted () {
            localStorage.removeItem('token')
            this.$store.commit('logoutUser')
            this.$router.push({ name: 'Login' })
        }
    }
</script>