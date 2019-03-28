<style>
</style>

<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <div hidden v-if="$route.query.location_id">
            <div class="animated fadeIn" ref="tenantsPerSqmRate">
                <b-row>
                    <b-col sm="12">
                        <b-card >
                            <table width="100%">
                                <tr style="border:none;">
                                    <td style="border: none" width="80%" class="align-center">
                                        <div style="font-size: 20pt;font-weight: 500;">{{company_info.company_name}}</div>
                                        <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.company_address}}</div>
                                        <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.email_address}}</div>
                                        <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.landline}}/{{company_info.mobile_number}}</div>
                                    </td>
                                    <td width="20%" style="object-fit: cover; border: none"><img :src="company_info.logo" style="height: 90px; width: 200px; text-align: right;"></td>
                                </tr>
                            </table>
                            <div style="font-size: 11pt; width: 100%;">
                                <div style="font-size: 16pt; font-weight: 500; text-align: center">TENANTS W/ PER SQM RATE</div>
                            </div>
                            <br>
                            <div style="font-size: 11pt; width: 100%;">
                                <div style="font-weight: 500;" v-if="location_id == 0">All Tenants</div>
                                <div style="font-weight: 500;" v-else>{{forms.location.fields.location_desc}} Tenants</div>
                            </div>
                            <table style="width: 100%; margin-top: 5px;">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;">Tenant Code</th>
                                        <th style="text-align:left;">Tenant</th>
                                        <th style="text-align:left;">Space Code</th>
                                        <th style="text-align:right;">Area (sqm)</th>
                                        <th style="text-align:right;">Rate Per Sqm(Disc)</th>
                                        <th style="text-align:right;">Rate Per Sqm(Basic)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="tenant in tenants">
                                        <td style="text-align:left;">{{tenant.tenant_code}}</td>
                                        <td style="text-align:left;">{{tenant.trade_name}}</td>
                                        <td style="text-align:left;">{{tenant.space_code}}</td>
                                        <td style="text-align:right;">{{formatNumber(tenant.contract_floor_area)}}</td>
                                        <td style="text-align:right;">{{formatNumber(tenant.disc_rate_sqm)}}</td>
                                        <td style="text-align:right;">{{formatNumber(tenant.basic_rate_sqm)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                        </b-card>
                    </b-col>
                </b-row>
            </div>
        </div>
        <div>
            <b-modal
                v-model="showModalLocation"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('location_id')"
            >
                <div slot="modal-title">
                    Tenants Per Sqm Rate
                </div>
                
                <b-row>
                    <b-col lg=12>
                        <b-form-group>
                            <label>Location</label>
                            <select2
                                ref="location_id"
                                :allowClear="false"
                                :placeholder="'Select Location'"
                                v-model="location_id"
                            >
                                <option value="0">All</option>
                                <option v-for="location in options.locations.items" :key="location.location_id" :value="location.location_id">{{  location.location_desc }}</option>
                            </select2>
                        </b-form-group>
                    </b-col>
                </b-row>
                <div slot="modal-footer">
                    <b-button variant="primary" @click="previewReport()">
                        <i class="fa fa-check"></i>
                        OK
                    </b-button>
                    <b-button variant="secondary" @click="showModalLocation=false">Close</b-button>            
                </div>
            </b-modal>
        </div>
    </div>
</template>
<script>
export default {
    name: 'tenantsPerSqmRate',
    data () {
        return {
            company_info: [],
            tenants: [],
            showModalLocation: false,
            location_id: 0,
            forms: {
                location: {
                    fields: {
                        location_desc: null
                    }
                }
            },
            options: {
                locations: {
                    items: []
                }
            },
            cssText: `
                body{
                    font-family: Calibri;
                    font-size: 8pt!important;
                }
                table {
                    font-size: 10pt!important;
                    border-collapse: collapse;
                }
                tr:last-child {
                    border-bottom: 1px solid gray;
                }
                td {
                    border-right: 1px solid gray;
                    border-left: 1px solid gray;
                }
                th {
                    border: 1px solid gray;
                }
                @page {
                    margin: 0.4in;
                }
                `
        }
    },
    async created(){
        if(this.$route.query.location_id){
            this.location_id = this.$route.query.location_id
            await this.fillEntityForm('location', this.location_id)
            await this.$http.get('/api/companysetting/1', {
            headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                const res = response.data
                this.company_info = res.data
            })
            .catch(error => {
                if (!error.response) return
                console.log(error)
            })

            await this.$http.get('api/reports/tenantspersqmrate/' + this.location_id, {
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                this.tenants = response.data
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
            console.log(this.d)
            await this.d.print(this.$refs.tenantsPerSqmRate, [this.cssText])
        }
        else{
            this.fillOptionsList('locations')
            this.showModalLocation = true
        }
    },
    mounted(){
        const { Printd } = window.printd
        this.d = new Printd()

        const { contentWindow } = this.d.getIFrame()
        contentWindow.addEventListener(
            'afterprint', () => window.close()
        )
    },
    methods: {
        previewReport(){
            if(this.location_id != null){
                let routeData = this.$router.resolve({name: 'Tenant Per Sqm Rate', query: {location_id: this.location_id}});
                window.open(routeData.href, '_blank');
            }
        }
    },
  }
</script>

