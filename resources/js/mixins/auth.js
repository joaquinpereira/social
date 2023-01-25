let user = document.head.querySelector('meta[name="user"]');
export default {
    computed:{
        currentUser(){
            return JSON.parse(user.content);
        },
        isAutenticated(){
            return !!user.content;
        },
        guest(){
            return ! this.isAutenticated;
        }
    }
}
