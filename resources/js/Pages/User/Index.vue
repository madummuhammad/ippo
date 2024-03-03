<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { ref, watch } from "vue";
import { candidatelist } from "@/common/data";
import { MoreHorizontalIcon } from "@zhuowenli/vue-feather-icons";
import Swal from "sweetalert2";
export default {
  props:{
    auth:Object
  },
  setup(props){
    let userData=ref([])
    let authData=ref(props.auth)

    console.log(props)
    const getUser=async ()=> {
      try {
        const response = await axios.get('/api/user/list');
        const data = response.data;

        console.log('list',data);

        userData.value=data.data;
      } catch (error) {
        console.error('error', error);
      }
    }

    getUser();

    const destroy=(event)=>{
      Swal.fire({
          title: "Hapus User?",
          text: "Semua data terkait user akan dihapus!",
          showCancelButton: true,
          cancelButtonColor: "#f46a6a",
          confirmButtonColor: "#34c38f",
          confirmButtonText: "Hapus!",
      }).then((result) => {
          if (result.isConfirmed) {
              const deleteEndpoint = 'api/user/destroy/'+event.id;

              axios.delete(deleteEndpoint)
                  .then(response => {
                    Swal.fire({
                      toast: true,
                      position: 'bottom-end',
                      showConfirmButton: false, 
                      timer: 3000, 
                      iconColor: 'white',
                      customClass: {
                          popup: 'colored-toast',
                      },
                      timerProgressBar: true, 
                      icon: 'success', 
                      title: 'User berhasil dihapus.'
                    });
                    getUser()
                  })
                  .catch(error => {
                      console.error(error);
                  });
          }
      });
    }

    return {
      userData,
      authData,
      destroy
    }

  },
  data() {
  },
  computed: {
  },
  watch: {
  },
  created() {
  },
  mounted() {
  },
  methods: {
  },
  components: {
    Layout,
    PageHeader,
    Swal,
    MoreHorizontalIcon,
    Head, Link
  },
};
</script>

<template>
  <Layout>
    <Head title="List View" />
    <PageHeader title="User List" pageTitle="User Lists" />
    <BRow class="g-4 mb-4">
      <!-- <BCol sm="auto">
        <div>
          <BLink href="#!" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> Add Candidate</BLink>
        </div>
      </BCol> -->
      <BCol sm>
        <div class="d-md-flex justify-content-sm-end gap-2">
          <div class="search-box ms-md-2 flex-shrink-0 mb-3 mb-md-0">
            <input type="text" class="form-control" id="searchJob" autocomplete="off"
              placeholder="Search for name...">
            <i class="ri-search-line search-icon"></i>
          </div>
          <!-- <div class="w-md">
            <select class="form-select" data-choices data-choices-search-false>
              <option value="All">All</option>
              <option value="Today">Today</option>
              <option value="Yesterday" selected>Yesterday</option>
              <option value="Last 7 Days">Last 7 Days</option>
              <option value="Last 30 Days">Last 30 Days</option>
              <option value="This Month">This Month</option>
              <option value="Last Year">Last Year</option>
            </select>
          </div> -->
        </div>
      </BCol>
    </BRow>

    <BRow class="gy-2 mb-2" id="candidate-list">
      <BCol lg="12" v-for="(item, index) of userData" :key="index">
        <BCard no-body class="mb-0">
          <BCardBody>
            <div class="d-lg-flex align-items-center justify-content-between">
              <!-- <div class="flex-shrink-0">
                <div class="avatar-sm rounded">
                  <img :src="data.img" alt="" class="member-img img-fluid d-block rounded">
                </div>
              </div> -->
              <div class="ms-3">
                <Link href="/pages/profile">
                  <h5 class="fs-16 mb-2">{{item.name}}</h5>
                </Link>
                <p class="text-muted mb-0">{{item.role}}</p>
              </div>
              <!-- <div class="d-flex gap-4 mt-0 text-muted mx-auto">
                <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> asdf</div>
                <div><i class="ri-time-line text-primary me-1 align-bottom"></i> <span class="badge" :class="{
                  'bg-success-subtle text-success': data.type == 'Full Time',
                  'bg-danger-subtle text-danger': data.type == 'Part Time',
                  'bg-primary-subtle text-primary': data.type == 'Freelancer',
                }">{{ data.type }}</span></div>
              </div> -->
              <!-- <div class="d-flex flex-wrap gap-2 align-items-center mx-auto">
                <BBadge tag="div" variant="bg-success" class="text-bg-success"> <i class="mdi mdi-star me-1"></i>4</BBadge>
                <div class="text-muted">4 Ratings</div>
              </div> -->
              <div>
                <!-- <BLink href="#!" class="btn btn-soft-success me-1">View Details</BLink> -->
                <!-- <BLink href="#!" class="btn btn-ghost-danger btn-icon custom-toggle" data-bs-toggle="button"
                  aria-pressed="false">
                  <span class="icon-on"><i class="ri-bookmark-line align-bottom"></i></span>
                  <span class="icon-off"><i class="ri-bookmark-3-fill align-bottom"></i></span>
                </BLink> -->
                <BDropdown variant="link" v-if="authData.user.id!==item.id"
                      toggle-class="btn btn-link text-primary p-1 mt-n2 py-0 text-decoration-none fs-15 arrow-none"
                      menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"
                      style="top: 3px;">
                      <template #button-content class="text-primary">
                        <MoreHorizontalIcon class="icon-sm"></MoreHorizontalIcon>
                      </template>
                      <Link class="dropdown-item" :href="route('user.edit',item.id)"><i
                        class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                      Edit </Link>
                      <div class="dropdown-divider"></div>
                      <BDropdownItem @click="destroy(item)"> <i
                          class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                        Remove
                      </BDropdownItem>
                    </BDropdown>
              </div>
            </div>
          </BCardBody>
        </BCard>
      </BCol>
      <BCol xxl="12" sm="12" class="project-card text-center" v-if="userData.length==0">
        <div class="p-5 card">
          Tidak ada data ditemukan
        </div>
      </BCol>

    </BRow>
  </Layout>
</template>