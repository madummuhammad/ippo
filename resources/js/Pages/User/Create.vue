<script>
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { ref, watch } from "vue";
import { Head, useForm, Link } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
export default {
  setup(){
    var isLoading=ref(false);
    let userForm=ref(useForm({
      name:null,
      email:null,
      phone:null,
      password:null,
      password_confirmation:null,
      role:null,
      outlet_id:null
    }));

    const addUser= ()=>{
      isLoading.value=true;
      // let postData
      // var prefix = '62';
      // var waValue = String(userForm.value.phone); // Mengonversi nilai form.wa menjadi string
      // if (!waValue.startsWith(prefix)) {
      //     userForm.value.phone = prefix + waValue;
      // }
      userForm.value.phone=parseInt(userForm.value.phone);
      console.log('userForm',userForm.value)

      userForm.value.transform((data) => ({
          ...data
      }))
      .post(route('user.store'), {
        onSuccess: () => {
          userForm.reset();
          isLoading.value=false;
        },
        onError:(errors)=>{
          isLoading.value=false;
          console.log('error')
        }
      });
    }

    let outletData=ref([])
    const getOutlet = async (event) => {
      try {
        const response = await axios.get('/api/outlet/list');
        const data = response.data;

        if(data.status=='success'){
          outletData.value=data.data;
        }

        console.log('outlet',data)
      } catch (error) {
        console.error('error', error);
      }
    };

    getOutlet();

    return {
      userForm,
      isLoading,
      outletData,
      addUser
    }
  },
  data() {
    return {
      defaultDateConfig: {
        dateFormat: "d M, Y",
        defaultDate: "",
      },
      date: "",
      togglePassword: false,
      togglePassword_conf: false,
    };
  },
  components: {
    Layout,
    PageHeader,
    flatPickr,
    Loading,
    Head
  },
};
</script>

<template>
  <div class="vl-parent">
    <loading v-model:active="isLoading" :loader="'dots'"/>
</div>
  <Layout>
    <Head title="New User" />
    <PageHeader title="New User" pageTitle="Users" />
    <BRow>
      <BCol lg="12">
        <BCard no-body>
            <BCardHeader>
              <h5 class="card-title mb-0">Create User</h5>
            </BCardHeader>
            <BCardBody>
              <BRow class="g-4">
                <!-- Nama -->
                <BCol lg="6">
                  <div>
                    <label for="nameInput" class="form-label">Nama<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nameInput" :class="{'is-invalid': userForm.errors.name}" placeholder="Masukan nama..." v-model="userForm.name" required />
                    <div class="invalid-feedback" v-if="userForm.errors.name">
                      {{ userForm.errors.name }}
                    </div>
                  </div>
                </BCol>

                <!-- Email -->
                <BCol lg="6">
                  <div>
                    <label for="emailInput" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="emailInput" :class="{'is-invalid': userForm.errors.email}" placeholder="Masukan email..." v-model="userForm.email" required />
                    <div class="invalid-feedback" v-if="userForm.errors.email">
                      {{ userForm.errors.email }}
                    </div>
                  </div>
                </BCol>

                <!-- Phone -->
                <BCol lg="6">
                  <div>
                    <label for="phoneInput" class="form-label">Phone <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text">+62</span>
                      <input type="number" class="form-control" id="phoneInput" :class="{'is-invalid': userForm.errors.phone}" placeholder="Masukan no telepon" v-model="userForm.phone" required />
                    </div>
                    <div class="invalid-feedback" v-if="userForm.errors.phone">
                      {{ userForm.errors.phone }}
                    </div>
                  </div>
                </BCol>

                <!-- Password -->
                <BCol lg="6">
                  <div>
                    <label for="passwordInput" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input :type="togglePassword ? 'text' : 'password'" class="form-control" id="passwordInput" :class="{'is-invalid': userForm.errors.password}" placeholder="Masukan password..." v-model="userForm.password" required />
                      <span variant="link" class="input-group-text text-decoration-none text-muted cursor-pointer" type="button" id="password-addon" @click="togglePassword = !togglePassword"> <i class="ri-eye-fill align-middle"></i></span>
                    </div>
                    <div class="invalid-feedback" v-if="userForm.errors.password">
                      {{ userForm.errors.password }}
                    </div>
                  </div>
                </BCol>

                <!-- Confirm Password -->
                <BCol lg="6">
                  <div>
                    <label for="passwordConfirmationInput" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input :type="togglePassword_conf ? 'text' : 'password'" class="form-control" id="passwordConfirmationInput" :class="{'is-invalid': userForm.errors.password_confirmation}" placeholder="Masukan password konfirmasi" v-model="userForm.password_confirmation" required />
                      <span variant="link" class="input-group-text text-decoration-none text-muted cursor-pointer" type="button" id="password-addon" @click="togglePassword_conf = !togglePassword_conf"> <i class="ri-eye-fill align-middle"></i></span>
                    </div>
                    <div class="invalid-feedback" v-if="userForm.errors.password_confirmation">
                      {{ userForm.errors.password_confirmation }}
                    </div>
                  </div>
                </BCol>

                <!-- Outlet -->
                <BCol lg="6">
                  <div>
                    <label for="outletInput" class="form-label">Outlet</label>
                    <select class="form-control" id="outletInput" :class="{'is-invalid': userForm.errors.outlet_id}" v-model="userForm.outlet_id" required>
                      <option :value="null">Pilih Outlet</option>
                      <option value="all">Semua Cabang</option>
                      <option :value="item.id" v-for="(item,index) in outletData">{{item.name}}</option>
                      <!-- Dinamis list outlet di sini -->
                    </select>
                    <div class="invalid-feedback" v-if="userForm.errors.outlet_id">
                      {{ userForm.errors.outlet_id }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="6">
                  <div>
                    <label for="outletInput" class="form-label">Role</label>
                    <select class="form-control" id="outletInput" :class="{'is-invalid': userForm.errors.role}" v-model="userForm.role" required>
                      <option value="">Pilih Role</option>
                      <option value="Super Admin">Super Admin</option>
                      <option value="Manager">Manager</option>
                      <option value="Staff Admin">Staf Admin</option>
                      <option value="PIC Cabang">PIC Cabang</option>
                      <!-- Dinamis list outlet di sini -->
                    </select>
                    <div class="invalid-feedback" v-if="userForm.errors.role">
                      {{ userForm.errors.role }}
                    </div>
                  </div>
                </BCol>

                <!-- Tombol -->
                <BCol lg="12">
                  <div class="hstack justify-content-end gap-2">
                    <BButton type="button" variant="danger">Kembali</BButton>
                    <BButton type="submit" variant="primary" @click="addUser">Submit</BButton>
                  </div>
                </BCol>
              </BRow>
            </BCardBody>
          </BCard>
        </BCol>
      </BRow>
    </Layout>
</template>