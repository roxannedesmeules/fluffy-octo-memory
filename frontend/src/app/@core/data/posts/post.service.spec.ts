import { HttpClientTestingModule, HttpTestingController } from "@angular/common/http/testing";
import { TestBed } from "@angular/core/testing";

import { PostService } from "./post.service";
import { Post } from "@core/data/posts/post.model";
import { ErrorResponse } from "@core/data/error-response.model";

describe("PostService", () => {
    let service: PostService;
    let http: HttpTestingController;

    const data = [
        { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1" },
        { id: 2, title: "test 2", slug: "test-2", featured: 1, summary: "test 2", content: "test 2" },
        { id: 3, title: "test 3", slug: "test-3", summary: "test 3", content: "test 3" },
        { id: 4, title: "test 4", slug: "test-4", featured: 1, summary: "test 4", content: "test 4" },
        { id: 5, title: "test 5", slug: "test-5", summary: "test 5", content: "test 5" },
    ];

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports   : [ HttpClientTestingModule ],
            providers : [ PostService ],
        });

        service = TestBed.get(PostService);
        http    = TestBed.get(HttpTestingController);
    });

    afterEach(() => {
        http.verify();
    });

    it("should be created", () => {
        expect(service).toBeTruthy();
    });

    describe("#findAll", () => {
        it("should return an Observable<Post[]> and get all data", () => {
            service.findAll().subscribe(result => {
                expect(result.length).toBe(5);
                expect(result).toEqual(data);
                expect(result).toEqual(jasmine.arrayContaining([jasmine.any(Post)]));
            });

            const req = http.expectOne(req => req.method === "GET" && req.url === "posts");
                  req.flush(data);

            expect(req.request.method).toBe("GET");
        });
    });

    describe("#findById", () => {
        it("should return an Observable<Post> and get a single Post", () => {
            const expected = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1" };

            service.findById(1).subscribe(result => {
                expect(result).toEqual(jasmine.any(Post));
                expect(result).toEqual(jasmine.objectContaining(expected));
            });

            const req = http.expectOne("posts/1");
                  req.flush(expected);

            expect(req.request.method).toBe("GET");
        });
    });

    describe("#findOne", () => {
        it("should return a 501 error", () => {
            service.findOne().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#featured", () => {
        it("should return an Observable<Post[]>", () => {
            const expected = [
                data[1],
                data[3],
            ];

            service.featured().subscribe(result => {
                expect(result.length).toEqual(2);
                expect(result).toEqual(expected);
                expect(result).toEqual(jasmine.arrayContaining([jasmine.any(Post)]));
            });

            const req = http.expectOne(req => req.method === "GET" && req.url === "posts");
                  req.flush(expected);

            expect(req.request.method).toBe("GET");
        });
    });

    describe("#latests", () => {
        it("should return an Observable<Post[]>", () => {
            const expected = [
                data[0],
                data[1],
                data[2],
            ];

            service.featured().subscribe(result => {
                expect(result.length).toEqual(3);
                expect(result).toEqual(expected);
                expect(result).toEqual(jasmine.arrayContaining([jasmine.any(Post)]));
            });

            const req = http.expectOne(req => req.method === "GET" && req.url === "posts");
            req.flush(expected);

            expect(req.request.method).toBe("GET");
        });
    });

    describe("#mapListToModelList", () => {
        it("should transform list to Post[]", () => {
            expect(service.mapListToModelList(data)).toEqual(jasmine.arrayContaining(data));
            expect(service.mapListToModelList(data)).toEqual(jasmine.arrayContaining([ jasmine.any(Post) ]));
        });
    });

    describe("#url", () => {
        it("should transform :baseUrl/:modelName/:id into posts", () => {
            expect(service.url()).toEqual("posts");
        });

        it("should transform :baseUrl/:modelName/:id into posts/1", () => {
            expect(service.url(1)).toEqual("posts/1");
        });
    });
});
