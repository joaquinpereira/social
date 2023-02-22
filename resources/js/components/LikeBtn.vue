<template>
    <div>
        <button
            :name="btn"
            @click="togleLike()"
            :class="getBtnTogleClass">
            <i :class="getIconClasses"></i>
            {{getBtnText}}
        </button>
    </div>
</template>

<script>
    export default {
        props:{
            model: {
                type: Object,
                required: true
            },
            url:{
                type: String,
                required: true
            },
            btn:{
                type: String,
                required: true
            }
        },
        methods: {
            togleLike(){
                let method = this.model.is_liked ? 'delete' : 'post';
                axios[method](this.url)
                .then(res =>{
                    this.model.is_liked = !this.model.is_liked;
                    this.model.likes_count = res.data.likes_count;
                })
            },
        },
        computed: {
            getBtnText(){
                return this.model.is_liked ? 'TE GUSTA' : 'ME GUSTA';
            },
            getBtnTogleClass(){
                return [
                    this.model.is_liked ? 'font-weight-bold' : '',
                    'btn', 'btn-link', 'btn-sm',
                ]
            },
            getIconClasses(){
                return [
                    this.model.is_liked ? 'far fa-thumbs-o-up' : 'fa fa-thumbs-up',
                    'text-primary', 'me-1',
                ]
            }
        }
    }
</script>
<style lang="scss" scoped>
    .comments-like-btn{
        button{ padding-left: 0 !important};
    }

</style>
