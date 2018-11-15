<script>
    export default {
      methods: {
        // 2nd parameter are callback functions pass as object
        createEntity (entity, cbFnObject) {
          this.forms[entity].isSaving = true
          this.resetFieldStates(entity)
          this.$http.post('api/' + entity, this.forms[entity].fields).then((response) => {
            this.forms[entity].isSaving = false
            this.clearFields(entity)
            this.$notify({
              type: 'success',
              group: 'notification',
              title: 'Success!',
              text: 'Successfully created entity.'
            })

            // if there is call function pass as success key, execute it and pass the response
            if (cbFnObject.success) cbFnObject.success(response)
          }).catch(error => {
            this.forms[entity].isSaving = false
            if (!error.response) return
            const errors = error.response.data.errors
            for (var key in errors) {
              this.forms[entity].states[key] = false
              this.forms[entity].errors[key] = errors[key]
            }

            // if there is call function pass as success key, execute it and pass the response
            if (cbFnObject.error) cbFnObject.error(error)
          })
        },

        updateEntity (entity) {
          this.resetFieldStates(entity)
          this.forms[entity].isSaving = true

          this.$http.put('api/' + entity + '/' + this.forms[entity].fields.id, this.forms[entity].fields)
            .then((response) => {
              this.forms[entity].isSaving = false
              this.$notify({
                type: 'success',
                group: 'notification',
                title: 'Success!',
                text: 'You have successfully updated the user.'
              })
            }).catch(error => {
              this.forms[entity].isSaving = false
              if (!error.response) return
              const errors = error.response.data.errors
              console.log(errors)
            })
        },

        clearFields (entity) {
          var fields = this.forms[entity].fields
          for (var keyField in fields) {
            if (typeof fields[keyField] !== 'object') {
              fields[keyField] = null
            } else {
              var innerFields = fields[keyField]
              for (var innerKey in innerFields) {
                innerFields[innerKey] = null
              }
            }
          }
        },

        resetFieldStates (entity) {
          var states = this.forms[entity].states
          for (var keyField in states) {
            if (typeof states[keyField] !== 'object') {
              states[keyField] = null
            } else {
              var innerStates = states[keyField]
              for (var innerKey in innerStates) {
                innerStates[innerKey] = null
              }
            }
          }
        },

        fillTableList (entity) {
          this.$http.get('/api/' + entity)
            .then((response) => {
              const records = response.data
              this.tables[entity].items = records.data
              console.log(this.tables[entity].items)
              this.paginations[entity].totalRows = records.data.length
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },

        fillOptionsList (entity) {
          this.$http.get('api/' + entity)
            .then((response) => {
              const items = response.data.data
              this.options[entity].items = items
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },

        fillEntityForm (entity, id) {
          this.$http.get('api/' + entity + '/' + id)
            .then((response) => {
              const items = response.data.data
              this.forms[entity].fields = items
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        }

      }
    }
</script>