<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <notifications group="notification" />
        <div class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Company Settings
                                <!-- <small class="font-italic">List of all registered user.</small> -->
                            </span>
                        </h5>
                        <b-form autocomplete="off">
                            <b-row>
                                <b-col sm="3">
                                </b-col>
                                <b-col sm="6">
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">* Company Name :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-input
                                                    ref="company_name"
                                                    id="company_name"
                                                    v-model="forms.companysetting.fields.company_name"
                                                    type="text"
                                                    placeholder="Company Name">
                                                </b-form-input>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">* Company Address :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-textarea
                                                    id="company_address"
                                                    v-model="forms.companysetting.fields.company_address"
                                                    type="text"
                                                    :rows="2"
                                                    placeholder="Company Address">
                                                </b-form-textarea>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">* Email Address :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-input
                                                    id="email_address"
                                                    v-model="forms.companysetting.fields.email_address"
                                                    type="text"
                                                    placeholder="Email Address">
                                                </b-form-input>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">Mobile Number :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-input
                                                    id="mobile_number"
                                                    v-model="forms.companysetting.fields.mobile_number"
                                                    type="text"
                                                    placeholder="Mobile Number">
                                                </b-form-input>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">Landline :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-input
                                                    id="landline"
                                                    v-model="forms.companysetting.fields.landline"
                                                    type="text"
                                                    placeholder="Mobile Number">
                                                </b-form-input>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg=3>
                                                <label class="col-form-label">Logo :</label>
                                            </b-col>
                                            <b-col lg=9>
                                                <div class="border" style="height: 150px; width: 200px;">
                                                    <b-img :src="forms.companysetting.fields.logo" width="200" height="150px"/>
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row class="mt-2">
                                            <b-col lg=3>
                                            </b-col>
                                            <b-col lg=9>
                                                <b-form-file @change="fieldChange" ref="file" accept=".jpg, .png, .gif" plain style="display: none;"></b-form-file>
                                                <b-btn variant="success" @click="$refs.file.$el.click()">
                                                    <i class="fa fa-file-image-o"></i> Browse
                                                </b-btn>
                                                <b-btn variant="danger" @click="$refs.file.reset(), forms.companysetting.fields.logo = null">
                                                    <i class="fa fa-times"></i> Remove
                                                </b-btn>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="3">
                                </b-col>
                            </b-row>
                        </b-form>
                        <hr>
                        <b-row class="pull-right mt-2">
                            <b-col sm="12">
                                <b-button 
                                    :disabled="forms.companysetting.isSaving" 
                                    variant="primary" 
                                    @click="onCompanySettingEntry">
                                    <icon v-if="forms.companysetting.isSaving" name="sync" spin></icon>
                                    <i class="fa fa-check"></i>
                                    Save
                                </b-button>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
export default {
    name: 'users',
    data () {
        return {
            entryMode: 'Edit',
            showModalEntry: false, //if true show modal
            showModalDelete: false,
            forms: {
                companysetting : {
                    isSaving: false,
                    fields: {
                        company_id: null,
                        company_name: null,
                        company_address: null,
                        email_address: null,
                        mobile_number: null,
                        landline: null,
                        logo: null
                    },
                }
            },
            image: new FormData,
            row: []
        }
    },
    methods:{
        onCompanySettingEntry () {
            if(this.entryMode == 'Add'){
                this.createEntity('user', true, 'users')
            }
            else{
                this.updateEntity('companysetting', 'company_id', true, this.row)
            }
        },
        onUserDelete(){
            this.deleteEntity('user', this.id, true, 'users', 'id')
        },
        setDelete(data){
            this.showModalDelete=true
            this.id = data.item.id
        },
        fieldChange(e){
            let attachment = e.target.files[0]
            let path = 'uploads/logo'

            this.image.append('image', attachment)
            this.image.append('path', path)

            this.$http.post('/api/upload', this.image, {
                headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token'),
                      'Content-Type' : 'multipart/form-data'
                  }
            })
            .then((response) => {
                this.forms.companysetting.fields.logo = response.data.path
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        }
    },
    created () {
        this.fillEntityForm('companysetting', 1)
    },
  }
</script>

