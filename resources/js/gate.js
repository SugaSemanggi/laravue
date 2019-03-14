export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'admin';
    }
    isOperator(){
        return this.user.type === 'operator';
    }
    isUser(){
        return this.user.type === 'user';
    }
    isAdminOrOperator(){
        if (this.user.type === 'admin' || this.user.type === 'operator'){
            return true;
        }
    }
    isUserOrOperator(){
        if (this.user.type === 'user' || this.user.type === 'operator'){
            return true;
        }
    }
}
