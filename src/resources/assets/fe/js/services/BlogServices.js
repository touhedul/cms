export default {
    methods: {
        addCommentCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/blog/comment/store',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        removeCommentCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/blog/comment/remove/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        }
    }
}