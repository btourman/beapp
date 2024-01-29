// import simpleRestProvider from "ra-data-simple-rest";
import {DataProvider, fetchUtils, GetListResult} from 'react-admin';
import inMemoryJWT from "./inMemoryJwt";
import {Options} from "ra-core/src/dataProvider/fetch";

const apiUrl = import.meta.env.VITE_REST_URL
// const httpClient = (url: string) => {
//     const options = {
//         headers: new Headers({ Accept: 'application/json' }),
//     };
//     const token = inMemoryJWT.getToken();
//     if (token) {
//         options.headers.set('Authorization', `Bearer ${token}`);
//     }
//
//     return fetchUtils.fetchJson(url, options);
// };

// export const dataProvider = simpleRestProvider(
//   import.meta.env.VITE_REST_URL, httpClient, 'X-Total-Count'
// );

const httpClient = (url: string, options: Options = {}) => {
    options.headers = new Headers({ Accept: 'application/json' })
    const token = inMemoryJWT.getToken();
    if (token) {
        options.headers.set('Authorization', `Bearer ${token}`);
    }

    console.log(options)

    if(options.body) {
        options.headers.set('Content-Type', 'application/json');
    }

    return fetchUtils.fetchJson(url, options);
};

export const dataProvider: DataProvider = {
        getList: async (resource: string): Promise<GetListResult> =>  {
            const url = `${apiUrl}/${resource}`;
            const {headers, json} = await httpClient(url);
            return {
                data: json,
                total: parseInt(headers.get('x-total-count') || ''),
            };
        },
        getOne: (resource: string, params: { id: string | number }) => {
            console.log(resource);
            console.log(params);
            return httpClient(`${apiUrl}/${resource}/${params.id}`).then(({ json }) => ({
                data: json,
            }))
        },
        getMany: () => Promise.reject(),
        getManyReference: () => Promise.reject(),
        update: (resource, params) => httpClient(`${apiUrl}/${resource}/${params.id}`, {
            method: 'PUT',
            body: JSON.stringify(params.data),
        }).then(({ json }) => ({ data: json })),
        updateMany: () => Promise.reject(),
        create: (resource, params) =>
            httpClient(`${apiUrl}/${resource}`, {
                method: 'POST',
                body: JSON.stringify(params.data),
            }).then(({ json }) => ({ data: json })),
        delete: (resource, params) =>
            httpClient(`${apiUrl}/${resource}/${params.id}`, {
                method: 'DELETE',
            }).then(({ json }) => ({ data: json })),
        deleteMany: (resource, params) =>
            Promise.all(
                params.ids.map(id =>
                    httpClient(`${apiUrl}/${resource}/${id}`, {
                        method: 'DELETE',
                    })
                )
            ).then(responses => ({
                data: responses.map(({ json }) => json.id),
            })),
};
