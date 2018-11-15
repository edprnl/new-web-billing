<template>
   <div class="animated fadeIn">
        <div class="container-fluid" >
            <div class="row">
                <div class="col-lg-12">
                    <b-card header="Tenants List"> 
                        <div class="row">
                            <div class="col-sm-4">
                                <b-button variant="primary">
                                        <i class="fa fa-plus-circle"></i> Create New Tenant
                                </b-button>
                            </div>

                            <div class="col-sm-4">
                                <span></span>
                            </div>

                            <div class="col-sm-4">
                                <b-form-input type="text" id="name" placeholder="Search"></b-form-input>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 mt-2">
                               <b-table 
                                         
                                         :items="items"
                                         :fields="fields"
                                        
                                         fixed hover >

                                    <template slot="action" slot-scope="data">
                                        <b-btn variant="primary" :to="{path: 'categories/' + data.item.id }">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn variant="danger" @click="onItemDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                               </b-table>
                            </div>

                            
                        </div>
                        
                    </b-card>
                </div>
            </div>
        </div>
   </div>
</template>

<script>
export default {
    name: 'departments',
    data () {
      return {
          //departments : [{}],
          fields:[
              {
                  key:'department_code',
                  label: 'Code'
              },
              {
                  key:'department_desc',
                  label: 'Desc'
              }
          ],
          items: [
              {
                department_code : 'aaa',
                department_desc : 'bbb'
              }
            ]
      }
      
    },
    methods:{
        // loadDepartments(){
        //    axios.get("api/departments").then(({ data }) => (this.departments = data));
        // }

    },
    created () {
      //this.loadDepartments();
    }
  }
</script>

