export default {
    methods: {
        savePageCall(data, callBackHandler) {
            axios({
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                method: 'post',
                url: '/api/pages/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(Helpers.unknownError(error));
            });
        },
        updatePageCall(id, data, callBackHandler) {
            axios({
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                method: 'post',
                url: '/api/pages/update/' + id,
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(Helpers.unknownError(error));
            });
        },
        deletePageCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/pages/remove/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', id);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}