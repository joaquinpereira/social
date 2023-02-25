<template>
    <div class="card bg-light shadow-sm">
        <a v-if="isAutenticated" class="btn btn-primary text-white position-absolute end-0 bottom-0"
            id="open_dialog_update_picture_profile"
            href="#"
            data-toggle="modal" data-target="#dialog_update_picture_profile">
           <i class="fa fa-camera"></i>
        </a>
        <img id="picture_profile" :src="user.avatar" :alt="user.name" class="card-img-top">
        <button v-if="isAutenticated"  type="button" id="btnOverlay" style="display: none;"  data-toggle="modal" data-target="#modal-overlay"></button>

        <div v-if="isAutenticated" class="modal fade" id="dialog_update_picture_profile">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="overlay" style="display: none;">
                        <i class="fa fa-3x fa-spinner fa-spin"></i>
                    </div>

                    <div class="modal-header">
                        <h4 class="modal-title">Actualizar foto de perfil</h4>
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="avatar" class="col-md-4 col-form-label text-md-end">Foto de perfil</label>

                            <div class="col-md-6">
                                <input
                                    id="avatar" type="file" accept="image/*" capture
                                    class="form-control" max="2048" name="avatar"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" id="close_modal" class="btn btn-outline-primary" data-dismiss="modal">Cerrar</button>
                        <button id="update_picture_profile" @click="uploadPhoto()" type="button" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            user:{
                type: Object,
                require: true
            }
        },
        data() {
            return {
                newPhoto: '',
            }
        },
        methods:{
            uploadPhoto(){
                $('.overlay').css('display','flex');
                $('#update_picture_profile').attr('disabled',true);
                axios.postForm(`users/${this.currentUser.name}`, {
                    '_method':'PUT',
                    'picture_update' : 'true',
                    'avatar': document.querySelector('#avatar').files[0]
                }).then(function(res){
                    window.location.reload();
                })
                .catch(function(err) {
                    console.log(err)
                    $('.overlay').css('display','none');
                });
            }
        }
    }
</script>
<style>
.modal-dialog .overlay {
    display: -ms-flexbox;
    display: flex;
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    margin: -1px;
    z-index: 1052;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: rgba(0,0,0,.7);
    color: #666f76;
    border-radius: 0.3rem;
}

</style>
