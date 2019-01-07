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

            this.tables[entity_table].items.unshift(response.data.data)
            this.paginations[entity_table].totalRows++

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

        createOptionsEntity (entity, isModal, entity_table) {
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

            this.options[entity_table].items.unshift(response.data.data)
            this.paginations[entity_table].totalRows++

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


        updateEntity (entity, entity_id, isModal, row) {
          this.forms[entity].isSaving = true
          this.resetFieldStates(entity)

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

              for (var key in response.data.data) {
                row[key] = response.data.data[key]
              }

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
              // this.forms[entity].isSaving = false
              // if (!error.response) return
              // const errors = error.response.data.errors
              // console.log(errors)
            })
        },

        // params[ entity = table_name, entity_id = primary_key, entity_field = specific field to update]
        deleteEntity (entity, entity_id, isModal, entity_table, primary_key) {
          this.resetFieldStates(entity)
          this.forms[entity].isSaving = true

          this.$http.put('api/' + entity + '/delete/' + entity_id, this.forms[entity].fields  ,{
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

              const index = this.tables[entity_table].items.findIndex(item => item[primary_key] === response.data.data[primary_key])

              this.$delete(this.tables[entity_table].items, index)
              this.paginations[entity_table].totalRows--

              if(isModal){
                this.showModalDelete = false
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
              if(typeof fields[keyField] == 'number'){
                fields[keyField] = 0
              }
              else{
                fields[keyField] = null
              }
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

        //fill table with filter
        filterTableList (entity, filter1, filter2) {
          this.$http.get('/api/' + entity + '/' + filter1 + '/' + filter2 ,{
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

        fillTableList (entity) {
          this.$http.get('/api/' + entity,{
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

        //fill options with filter
        filterOptionsList (entity, filter) {
          this.$http.get('api/' + entity + '/' + filter,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              const items = response.data.data
              this.options[entity].items = items
            })
            .catch(error => {
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
            })
            .catch(error => {
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
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },

        // function which checks if id of entity was used
        async checkIfUsed (entity, filter) {
          let is_used = false
          this.forms[entity].isDeleting = true
          await this.$http.get('api/' + entity + 'check/' + filter,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
              this.forms[entity].isDeleting = false
              is_used = response.data
            })
            .catch(error => {
              this.forms[entity].isDeleting = false
              if (!error.response) return
              console.log(error)
            })
          return is_used
        },
        // format number into 2 decimal places with comma
        formatNumber(value) {
            let val = (value/1).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        // format date with date and format as parameters
        moment: function (date, format) {
            return moment(date).format(format);
        },
        // focus element using refs
        focusElement(e, is_el = false){
            if(is_el){
              this.$refs[e].$el.focus()
            }
            else{
              this.$refs[e].focus()
            }
        }
      }
    }
</script>