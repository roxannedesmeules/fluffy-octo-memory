import { TestBed, inject } from '@angular/core/testing';

import { PostTagService } from './post-tag.service';

describe('PostTagService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PostTagService]
    });
  });

  it('should be created', inject([PostTagService], (service: PostTagService) => {
    expect(service).toBeTruthy();
  }));
});
