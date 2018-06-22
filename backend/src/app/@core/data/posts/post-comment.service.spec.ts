import { TestBed, inject } from '@angular/core/testing';

import { PostCommentService } from './post-comment.service';

describe('PostCommentService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PostCommentService]
    });
  });

  it('should be created', inject([PostCommentService], (service: PostCommentService) => {
    expect(service).toBeTruthy();
  }));
});
