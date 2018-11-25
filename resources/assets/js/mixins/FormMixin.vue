<script>
    export default {
      methods: {
        // 2nd parameter are callback functions pass as object
        createEntity (entity, isModal, entity_table) {
          this.forms[entity].isSaving = true
          this.resetFieldStates(entity)
          this.$http.post('api/' + entity, this.forms[entity].fields,{
             headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
          })
          .then((response) => {  
            this.forms[entity].isSaving = false
            this.clearFields(entity)
            this.$notify({
              type: 'success',
              group: 'notification',
              title: 'Success!',
              text: 'The record has been successfully created.'
            })
            this.fillTableList(entity_table)
            if(isModal){
              this.showModalEntry = false
            }
            else{
              this.showEntry = false
            }

          }).catch(error => {
            this.forms[entity].isSaving = false
            if (!error.response) return
            const errors = error.response.data.errors
            for (var key in errors) {
              this.forms[entity].states[key] = false
              this.forms[entity].errors[key] =  errors[key]
            }
          })
        },

        updateEntity (entity, entity_id, isModal, entity_table) {
          this.resetFieldStates(entity)
          this.forms[entity].isSaving = true

          this.$http.put('api/' + entity + '/' + this.forms[entity].fields[entity_id], this.forms[entity].fields ,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              this.forms[entity].isSaving = false
              this.$notify({
                type: 'success',
                group: 'notification',
                title: 'Success!',
                text: 'The record has been successfully updated.'
              })
              this.fillTableList(entity_table)
              if(isModal){
                this.showModalEntry = false
              }
              else{
                this.showEntry = false
              }
              
            }).catch(error => {
              this.forms[entity].isSaving = false
              if (!error.response) return
              const errors = error.response.data.errors
              console.log(errors)
            })
        },

        // params[ entity = table_name, entity_id = primary_key, entity_field = specific field to update]
        deleteEntity (entity, entity_id, isModal, entity_table) {
          this.resetFieldStates(entity)
          this.forms[entity].isSaving = true

          this.$http.delete('api/' + entity + '/' + entity_id, this.forms[entity].fields  ,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              this.forms[entity].isSaving = false
              this.$notify({
                type: 'success',
                group: 'notification',
                title: 'Success!',
                text: 'The record has been deleted.'
              })
              this.fillTableList(entity_table)
              if(isModal){
                this.showModalEntry = false
              }
              else{
                this.showEntry = false
              }
              
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
          this.$http.get('/api/' + entity ,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              const records = response.data
              this.tables[entity].items = records.data
              this.paginations[entity].totalRows = records.data.length
              // ### commented for future server side pagination
              // this.paginations[entity].totalRows = records.meta.total
              // this.paginations[entity].currentPage = records.meta.current_page
              // this.paginations[entity].perPage = records.meta.per_page
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },

        

        fillOptionsList (entity) {
          this.$http.get('api/' + entity,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              const items = response.data.data
              this.options[entity].items = items
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },

        fillEntityForm (entity, id) {
          this.$http.get('api/' + entity + '/' + id,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
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