<?php
namespace ARIPD\AdminBundle\Util;

class ARIPDVideo {
	
	private $url;
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	public function video_image() {
		$image_url = parse_url($this->url);
		if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
			$array = explode("&", $image_url['query']);
			$videoId = substr($array[0], 2);
			//return "http://img.youtube.com/vi/".$videoId."/default.jpg"; // Small Default
			return "http://img.youtube.com/vi/".$videoId."/0.jpg"; // Large Default
			//return "http://img.youtube.com/vi/".$videoId."/1.jpg";
			//return "http://img.youtube.com/vi/".$videoId."/2.jpg";
			//return "http://img.youtube.com/vi/".$videoId."/3.jpg";
		}
		elseif($image_url['host'] == 'www.youtu.be' || $image_url['host'] == 'youtu.be') {
			$array = explode("/", $image_url['path']);
			$videoId = $array[1];
			//return "http://img.youtube.com/vi/".$videoId."/default.jpg"; // Small Default
			return "http://img.youtube.com/vi/".$videoId."/0.jpg"; // Large Default
			//return "http://img.youtube.com/vi/".$videoId."/1.jpg";
			//return "http://img.youtube.com/vi/".$videoId."/2.jpg";
			//return "http://img.youtube.com/vi/".$videoId."/3.jpg";
		}
		elseif($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
			$videoId = substr($image_url['path'], 1);
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videoId.".php"));
			//return $hash[0]["thumbnail_small"];
			//return $hash[0]["thumbnail_medium"];
			return $hash[0]["thumbnail_large"];
		}
	}
	
	public function video_embedurl() {
		$image_url = parse_url($this->url);
		if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
			$array = explode("&", $image_url['query']);
			$videoId = substr($array[0], 2);
			return sprintf('http://www.youtube.com/embed/%s', $videoId);
		}
		elseif($image_url['host'] == 'www.youtu.be' || $image_url['host'] == 'youtu.be') {
			$array = explode("/", $image_url['path']);
			$videoId = $array[1];
			return sprintf('http://www.youtube.com/embed/%s', $videoId);
		}
		elseif($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
			$videoId = substr($image_url['path'], 1);
			return sprintf('http://player.vimeo.com/video/%s', $videoId);
		}
	}
	
	public function video_embed($width, $height) {
		$image_url = parse_url($this->url);
		if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
			$array = explode("&", $image_url['query']);
			$videoId = substr($array[0], 2);
			$embed_url = sprintf('http://www.youtube.com/embed/%s', $videoId);
			return sprintf('<iframe src="http://www.youtube.com/embed/%s?wmode=opaque" width="%s" height="%s" frameborder="0" allowfullscreen></iframe>', $videoId, $width, $height);
		}
		elseif($image_url['host'] == 'www.youtu.be' || $image_url['host'] == 'youtu.be') {
			$array = explode("/", $image_url['path']);
			$videoId = $array[1];
			$embed_url = sprintf('http://www.youtube.com/embed/%s', $videoId);
			return sprintf('<iframe src="http://www.youtube.com/embed/%s?wmode=opaque" width="%s" height="%s" frameborder="0" allowfullscreen></iframe>', $videoId, $width, $height);
		}
		elseif($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
			$videoId = substr($image_url['path'], 1);
			$embed_url = sprintf('http://player.vimeo.com/video/%s', $videoId);
			return sprintf('<iframe src="http://player.vimeo.com/video/%s?color=ffffff" width="%s" height="%s" frameborder="0" allowFullScreen webkitAllowFullScreen mozallowfullscreen></iframe>', $videoId, $width, $height);
		}
	}
	
	public function video_info() {
	
		// Handle Youtube
		if (strpos($this->url, "youtube.com")) {
			$url = parse_url($this->url);
			$vid = parse_str($url['query'], $output);
			$video_id = $output['v'];
			$data['video_type'] = 'youtube';
			$data['video_id'] = $video_id;
			$xml = simplexml_load_file("http://gdata.youtube.com/feeds/api/videos?q=$video_id");
	
			foreach ($xml->entry as $entry) {
				// get nodes in media: namespace
				$media = $entry->children('http://search.yahoo.com/mrss/');
	
				// get video player URL
				$attrs = $media->group->player->attributes();
				$watch = $attrs['url'];
	
				// get video thumbnail
				$data['thumb_1'] = $media->group->thumbnail[0]->attributes(); // Thumbnail 1
				$data['thumb_2'] = $media->group->thumbnail[1]->attributes(); // Thumbnail 2
				$data['thumb_3'] = $media->group->thumbnail[2]->attributes(); // Thumbnail 3
				$data['thumb_large'] = $media->group->thumbnail[3]->attributes(); // Large thumbnail
				$data['tags'] = $media->group->keywords; // Video Tags
				$data['cat'] = $media->group->category; // Video category
				$attrs = $media->group->thumbnail[0]->attributes();
				$thumbnail = $attrs['url'];
	
				// get <yt:duration> node for video length
				$yt = $media->children('http://gdata.youtube.com/schemas/2007');
				$attrs = $yt->duration->attributes();
				$data['duration'] = $attrs['seconds'];
	
				// get <yt:stats> node for viewer statistics
				$yt = $entry->children('http://gdata.youtube.com/schemas/2007');
				$attrs = $yt->statistics->attributes();
				$data['views'] = $viewCount = $attrs['viewCount'];
				$data['title']=$entry->title;
				$data['info']=$entry->content;
	
				// get <gd:rating> node for video ratings
				$gd = $entry->children('http://schemas.google.com/g/2005');
				if ($gd->rating) {
					$attrs = $gd->rating->attributes();
					$data['rating'] = $attrs['average'];
				} 
				else {
					$data['rating'] = 0;
				}
			} // End foreach
		} // End Youtube
	
		// Handle Vimeo
		else if (strpos($this->url, "vimeo.com")) {
			$video_id=explode('vimeo.com/', $this->url);
			$video_id=$video_id[1];
			$data['video_type'] = 'vimeo';
			$data['video_id'] = $video_id;
			$xml = simplexml_load_file("http://vimeo.com/api/v2/video/$video_id.xml");
	
			foreach ($xml->video as $video) {
				$data['id']=$video->id;
				$data['title']=$video->title;
				$data['info']=$video->description;
				$data['url']=$video->url;
				$data['upload_date']=$video->upload_date;
				$data['mobile_url']=$video->mobile_url;
				$data['thumb_small']=$video->thumbnail_small;
				$data['thumb_medium']=$video->thumbnail_medium;
				$data['thumb_large']=$video->thumbnail_large;
				$data['user_name']=$video->user_name;
				$data['urer_url']=$video->urer_url;
				$data['user_thumb_small']=$video->user_portrait_small;
				$data['user_thumb_medium']=$video->user_portrait_medium;
				$data['user_thumb_large']=$video->user_portrait_large;
				$data['user_thumb_huge']=$video->user_portrait_huge;
				$data['likes']=$video->stats_number_of_likes;
				$data['views']=$video->stats_number_of_plays;
				$data['comments']=$video->stats_number_of_comments;
				$data['duration']=$video->duration;
				$data['width']=$video->width;
				$data['height']=$video->height;
				$data['tags']=$video->tags;
			} // End foreach
		} // End Vimeo
	
		// Set false if invalid URL
		else { 
			$data = false;
		}
	
		return $data;
	
	}
	
}
